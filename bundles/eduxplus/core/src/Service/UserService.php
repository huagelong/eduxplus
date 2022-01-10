<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/8 13:43
 */

namespace Eduxplus\CoreBundle\Service;


use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Eduxplus\CoreBundle\Entity\BaseRoleUser;
use Eduxplus\CoreBundle\Entity\BaseUser;
use Eduxplus\CoreBundle\Lib\Service\RedisService;
use Knp\Component\Pager\PaginatorInterface;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\CoreBundle\Lib\Service\Base\MobileMaskService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService extends AdminBaseService
{

    protected $paginator;
    protected $userPasswordEncoder;
    protected $mobileMaskService;
    protected $redisService;

    public function __construct(
        PaginatorInterface $paginator,
        UserPasswordHasherInterface $userPasswordEncoder,
        MobileMaskService $mobileMaskService,
        RedisService $redisService
    ) {
        $this->paginator = $paginator;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->mobileMaskService = $mobileMaskService;
        $this->redisService = $redisService;
    }

    public function userList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM Core:BaseUser a " . $sql  . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        return $pagination;
    }

    public function checkDisplayName($name, $id = 0)
    {
        $sql = "SELECT a FROM Core:BaseUser a where a.displayName =:displayName ";
        $params = [];
        $params['displayName'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function checkFullName($name, $id = 0)
    {
        $sql = "SELECT a FROM Core:BaseUser a where a.fullName =:fullName ";
        $params = [];
        $params['fullName'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function searchAdminFullNameFormat($name)
    {
        $users = $this->searchAdminFullName($name);
        if (!$users) return [];
        $rs = [];
        foreach ($users as $v) {
            $tmp = [];
            $tmp['id'] = $v['id'];
            $tmp['text'] = $v['fullName'];
            $rs[] = $tmp;
        }
        return $rs;
    }

    public function searchAdminFullName($name)
    {
        $sql = "SELECT a FROM Core:BaseUser a where a.fullName like :fullName AND a.isAdmin=1 ";
        $params = [];
        $params['fullName'] = "%" . $name . "%";
        return $this->fetchAll($sql, $params);
    }

    

    public function searchFullNameFormat($name)
    {
        $users = $this->searchFullName($name);
        if (!$users) return [];
        $rs = [];
        foreach ($users as $v) {
            $tmp = [];
            $tmp['id'] = $v['id'];
            $tmp['text'] = $v['fullName'];
            $rs[] = $tmp;
        }
        return $rs;
    }

    public function searchFullName($name)
    {
        $sql = "SELECT a FROM Core:BaseUser a where a.fullName like :fullName";
        $params = [];
        $params['fullName'] = "%" . $name . "%";
        return $this->fetchAll($sql, $params);
    }

    public function checkMobile($name, $id = 0)
    {
        $mobileMask =  $this->mobileMaskService->encrypt($name);

        $sql = "SELECT a FROM Core:BaseUser a where a.mobileMask =:mobileMask ";
        $params = [];
        $params['mobileMask'] = $mobileMask;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function add($mobile, $displayName, $pwd1, $fullName, $sex, $roles)
    {
        $model = new BaseUser();
        $model->setMobile($mobile);
        $mobileMask =  $this->mobileMaskService->encrypt($mobile);
        $model->setMobileMask($mobileMask);
        $model->setMobileTail(substr($mobile, -6));
        $model->setSno($this->getSno($mobile));
        $model->setGravatar($this->getOption("app.user.default.gravatar", 1, 0));
        $pwd = $this->userPasswordEncoder->hashPassword($model, $pwd1);
        $model->setPassword($pwd);
        $model->setFullName($fullName);
        $model->setDisplayName($displayName);
        $model->setSex($sex);
        $model->setRegSource("admin");
        if ($roles) {
            $model->setIsAdmin(1);
        }
        $uid = $this->save($model);

        if ($roles) {
            foreach ($roles as $roleId) {
                $modelRole = new BaseRoleUser();
                $modelRole->setRoleId($roleId);
                $modelRole->setUid($uid);
                $this->save($modelRole);
            }
        }
        return $uid;
    }

    /**
     * 获取学号
     * @param $mobile
     * @return string
     */
    public function getSno($mobile){
        $snoTmp = substr($mobile, -6);
        $redisKey = "user_no_incr:".$mobile;
        if($this->redisService->exists($redisKey)) {
            $rcount = $this->redisService->incr($redisKey);
            if ($rcount > 1) {
                return $snoTmp . $rcount;
            } else {
                return $snoTmp;
            }
        }
        $sql = "SELECT count(a.id) FROM Core:BaseUser a WHERE a.mobileTail=:mobileTail";
        $count = $this->fetchColumnBySql($sql, ["mobileTail"=>$snoTmp],"c");
        if($count == 0){
            $this->redisService->incr($redisKey);
            return $snoTmp;
        }
        $count = $count+1;
        $this->redisService->incrby($redisKey, $count);
        return $snoTmp.$count;
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id' => $id]);
    }

    public function searchResult($id)
    {
        $info = $this->getById($id);
        return [$info['fullName'] => $info['id']];
    }

    public function getMyRoleIds($uid)
    {
        $sql = "SELECT a FROM Core:BaseRoleUser a WHERE a.uid=:uid";
        return $this->fetchFields('roleId', $sql, ['uid' => $uid]);
    }

    public function edit($id, $displayName, $fullName, $sex, $roles)
    {
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setFullName($fullName);
        $model->setDisplayName($displayName);
        $model->setSex($sex);
        if ($roles) {
            $model->setIsAdmin(1);
        } else {
            $model->setIsAdmin(0);
        }
        $uid = $this->save($model);

        if ($roles) {
            //先删除
            $sql = "SELECT a FROM Core:BaseRoleUser a WHERE a.uid=:uid";
            $models = $this->fetchAll($sql, ['uid' => $id], 1);
            $this->hardDelete($models);
            //后添加
            foreach ($roles as $roleId) {
                $modelRole = new BaseRoleUser();
                $modelRole->setRoleId($roleId);
                $modelRole->setUid($uid);
                $this->save($modelRole);
            }
        }
        return $uid;
    }

    public function delUser($id)
    {
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ["id" => $id], 1);
        $this->delete($model);
    }

    public function switchLock($id, $state)
    {
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setIsLock($state);
        return $this->save($model);
    }
}
