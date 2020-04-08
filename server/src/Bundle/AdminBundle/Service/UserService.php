<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/8 13:43
 */

namespace App\Bundle\AdminBundle\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Bundle\AppBundle\Lib\Service\HelperService;
use App\Entity\BaseUser;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService extends BaseService
{

    protected $paginator;
    protected $userPasswordEncoder;

    public function __construct(PaginatorInterface $paginator, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->paginator = $paginator;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function userList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);
        $values = $request->get("values");
        $isAdmin = $values['_isAdmin'];
        if($isAdmin == 1){
            if($sql){
                $sql .= " AND a.roles LIKE '%ROLE_ADMIN%'";
            }else{
                $sql = " WHERE a.roles LIKE '%ROLE_ADMIN%'";
            }
        }elseif($isAdmin == 0){
            if($sql) {
                $sql .= " AND a.roles NOT LIKE '%ROLE_ADMIN%'";
            }else{
                $sql .= " WHERE a.roles NOT LIKE '%ROLE_ADMIN%'";
            }
        }

        $dql = "SELECT a FROM App:BaseUser a " . $sql;
        dump($dql);
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if($items){
            foreach ($items as $v){
                $vArr =  $this->toArray($v);
                $roles = $vArr['roles'];
                $isAdmin  =  in_array("ROLE_ADMIN", $roles);
                $vArr['isAdmin'] = $isAdmin;
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function checkDisplayName($name, $id=0){
        $sql = "SELECT a FROM App:BaseUser a where a.displayName =:displayName ";
        $params = [];
        $params['displayName'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function checkFullName($name, $id=0){
        $sql = "SELECT a FROM App:BaseUser a where a.fullName =:fullName ";
        $params = [];
        $params['fullName'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function checkMobile($name, $id=0){
        $sql = "SELECT a FROM App:BaseUser a where a.mobile =:mobile ";
        $params = [];
        $params['mobile'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function add($mobile, $displayName, $pwd1, $fullName, $sex, $roles){
        $helperService = new HelperService();
        $uuid = $helperService->getUuid();
        $model = new BaseUser();
        $model->setMobile($mobile);
        $model->setUuid($uuid);
        $model->setGravatar("/assets/images/defaultFace.jpeg");
        $pwd = $this->userPasswordEncoder->encodePassword($model, $pwd1);
        $model->setPassword($pwd);
        $model->setFullName($fullName);
        $model->setDisplayName($displayName);
        $model->setSex($sex);
        if($roles){
            $model->setRoles(['ROLE_ADMIN']);
        }
        $uid = $this->save($model);
        

    }

}
