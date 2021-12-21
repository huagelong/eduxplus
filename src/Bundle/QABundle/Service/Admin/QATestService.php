<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/12/6 19:24
 */

namespace App\Bundle\QABundle\Service\Admin;


use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use App\Bundle\QABundle\Entity\TeachTest;
use Knp\Component\Pager\PaginatorInterface;

class QATestService  extends AdminBaseService
{
    protected $paginator;
    protected $userService;

    public function __construct(PaginatorInterface $paginator, UserService $userService)
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
    }

    public function getList($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM QA:TeachTest a " . $sql . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);

        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if ($items) {
            foreach ($items as $v) {
                $vArr = $this->toArray($v);
                $createrUid = $vArr['createUid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $cateSql = "SELECT a FROM Core:TeachCategory a WHERE a.id=:id ";
                $cateInfo = $this->fetchOne($cateSql, ["id"=>$vArr['categoryId']]);
                $vArr['category'] = $cateInfo['name'];
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function add($uid, $name, $categoryId, $sort,$status, $expireTime){
        $model = new TeachTest();
        $model->setCreateUid($uid);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setName($name);
        $model->setSort($sort);
        $model->setExpireTime($expireTime);
        return $this->save($model);
    }

    public function edit($id, $name, $categoryId, $sort,$status, $expireTime){
        $sql = "SELECT a FROM QA:TeachTest a WHERE a.id=:id ";
        $model = $this->fetchOne($sql, ['id'=>$id], 1);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setName($name);
        $model->setSort($sort);
        $model->setExpireTime($expireTime);
        return $this->save($model);
    }


    public function del($id){
        $sql = "SELECT count(a.id) as cnt FROM QA:TeachTestSub a WHERE a.testId = :testId";
        $sub = $this->fetchField("cnt", $sql, ["testId"=>$id]);
        if($sub>0) return $this->error()->add("请先删除试题数据");
        $sql = "SELECT a FROM QA:TeachTest a WHERE a.id=:id ";
        $model = $this->fetchOne($sql, ['id'=>$id], 1);
        $this->delete($model);
        return true;
    }

    public function checkName($name, $id=0){
        $sql = "SELECT a FROM QA:TeachTest a where a.name =:name ";
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }


    public function searchProductName($name)
    {
        $sql = "SELECT a FROM QA:TeachTest a WHERE a.name like :name AND a.status=1 ";
        $params = [];
        $params['name'] = "%" . $name . "%";
        $all = $this->fetchAll($sql, $params);
        if (!$all) return [];
        $rs = [];
        foreach ($all as $v) {
            $tmp = [];
            $tmp['id'] = $v['id'];
            $tmp['text'] = $v['name'];
            $rs[] = $tmp;
        }
        return $rs;
    }


    public function getById($id){
        $sql = "SELECT a FROM QA:TeachTest a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id' => $id]);
    }

    public function switchStatus($id, $state){
        $sql = "SELECT a FROM QA:TeachTest a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->save($model);
    }

}
