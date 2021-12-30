<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 16:06
 */

namespace Eduxplus\EduxBundle\Service\Teach;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\EduxBundle\Service\Jw\SchoolService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\EduxBundle\Entity\TeachProducts;
use Knp\Component\Pager\PaginatorInterface;

class ProductService extends AdminBaseService
{
    protected $paginator;
    protected $categoryService;
    protected $userService;
    protected $schoolService;
    protected $agreementService;

    public function __construct(PaginatorInterface $paginator, CategoryService $categoryService, UserService $userService, AgreementService $agreementService)
    {
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->userService = $userService;
        $this->agreementService = $agreementService;
    }

    public function getList($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);
        $dql = "SELECT a FROM Edux:TeachProducts a " . $sql . " ORDER BY a.id DESC";
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
                $vArr =  $this->toArray($v);
                $cateGoryId = $vArr['categoryId'];
                $createrUid = $vArr['createUid'];
                $agreementId = $vArr['agreementId'];
                $cateGroy = $this->categoryService->getById($cateGoryId);
                $vArr['category'] = $cateGroy['name'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];
                $agreement = $this->agreementService->getById($agreementId);
                $vArr['agreement'] = $agreement['name'];
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }

    public function searchAdminFullName($name)
    {
        $users = $this->userService->searchAdminFullName($name);
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
        $users = $this->userService->searchFullName($name);
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


    public function searchProductName($name)
    {
        $sql = "SELECT a FROM Edux:TeachProducts a WHERE a.name like :name AND a.status=1 ";
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

    public function getAgreements()
    {
        $schools = $this->agreementService->getAll();
        if (!$schools) return [];
        $result = [];
        foreach ($schools as $v) {
            $result[$v['name']] = $v['id'];
        }
        return $result;
    }

    public function add($uid, $name, $agreementId, $status, $maxMemberNumber, $categoryId, $descr)
    {
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);

        $model = new TeachProducts();
        $model->setName($name);
        $model->setDescr($descr);
        $model->setStatus($status);
        $model->setFirstCategoryId($brandId);
        $model->setCategoryId($categoryId);
        $model->setAgreementId($agreementId);
        $model->setMaxMemberNumber($maxMemberNumber);
        $model->setCreateUid($uid);
        return $this->save($model);
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Edux:TeachProducts a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id' => $id]);
    }

    public function getByName($name, $id = 0)
    {
        $sql = "SELECT a FROM Edux:TeachProducts a WHERE a.name=:name";
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function edit($id, $name, $agreementId, $status, $maxMemberNumber, $categoryId, $descr)
    {
        $cate = $this->categoryService->getById($categoryId);
        $path = trim($cate['findPath'], ',');
        $pathArr = explode(",", $path);
        $brandId = end($pathArr);

        $courceSql = "SELECT a FROM Edux:TeachProducts a WHERE a.id=:id";
        $model = $this->fetchOne($courceSql, ['id' => $id], 1);
        $model->setName($name);
        $model->setDescr($descr);
        $model->setStatus($status);
        $model->setFirstCategoryId($brandId);
        $model->setCategoryId($categoryId);
        $model->setAgreementId($agreementId);
        $model->setMaxMemberNumber($maxMemberNumber);
        return $this->save($model);
    }

    public function del($id)
    {
        $sql = "SELECT a FROM Edux:TeachProducts a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        return $this->delete($model);
    }

    public function switchStatus($id, $state)
    {
        $sql = "SELECT a FROM Edux:TeachProducts a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->save($model);
    }
}
