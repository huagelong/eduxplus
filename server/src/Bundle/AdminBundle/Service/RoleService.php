<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/4 11:07
 */

namespace App\Bundle\AdminBundle\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\BaseRole;
use App\Entity\BaseRoleMenu;
use Knp\Component\Pager\PaginatorInterface;

class RoleService extends BaseService
{

    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function roleMenu($request, $page, $pageSize){
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM App:BaseRole a " . $sql;
        dump($dql);
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );
        return $pagination;
    }

    public function addRole($name, $isLock, $descr){
        $model = new BaseRole();
        $model->setName($name);
        $model->setIsLock($isLock);
        $model->setDescr($descr);
        $this->save($model);
    }

    public function updateRole($id, $name, $isLock, $descr){
        $sql = "SELECT a FROM App:BaseRole a where a.id =:id ";
        $params = [];
        $params['id'] = $id;
        $model = $this->fetchOne($sql, $params, 1);
        $model->setName($name);
        $model->setIsLock($isLock);
        $model->setDescr($descr);
        $this->save($model);
    }

    public function checkName($name, $id=0){
        $sql = "SELECT a FROM App:BaseRole a where a.name =:name ";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }

    public function getById($id){
        $sql = "SELECT a FROM App:BaseRole a where a.id =:id ";
        $params = [];
        $params['id'] = $id;
        return $this->fetchOne($sql, $params);
    }

    public function deleteRole($id){
        $sql = "SELECT a FROM App:BaseRole a where a.id =:id ";
        $params = [];
        $params['id'] = $id;
        $model = $this->fetchOne($sql, $params, 1);
        $this->delete($model);
        return true;
    }

    public function bindMenu($roleId, $menuIds){
        //先删除
        $sql = "SELECT a FROM App:BaseRoleMenu a WHERE a.roleId=:roleId";
        $models = $this->fetchAll($sql, ['roleId'=>$roleId], 1);
        $this->hardDelete($models);
        if($menuIds){
            foreach ($menuIds as $menuId){
                $model = new BaseRoleMenu();
                $model->setMenuId($menuId);
                $model->setRoleId($roleId);
                $this->save($model);
            }
        }
        return true;
    }

    public function getRoleMenu($roleId){
        $sql = "SELECT a.menuId FROM App:BaseRoleMenu a WHERE a.roleId=:roleId";
        $menuIds = $this->fetchFields("menuId", $sql, ['roleId'=>$roleId]);
        return $menuIds;
    }


}
