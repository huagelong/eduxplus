<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/4 11:07
 */

namespace Eduxplus\CoreBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\CoreBundle\Entity\BaseRole;
use Eduxplus\CoreBundle\Entity\BaseRoleMenu;
use Knp\Component\Pager\PaginatorInterface;

class RoleService extends AdminBaseService
{

    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function roleMenu($request, $page, $pageSize)
    {
        $sql = $this->getFormatRequestSql($request);

        $dql = "SELECT a FROM Core:BaseRole a " . $sql  . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );
        return $pagination;
    }

    public function addRole($name, $isLock, $descr)
    {
        $model = new BaseRole();
        $model->setName($name);
        $model->setIsLock($isLock);
        $model->setDescr($descr);
        $this->db()->save($model);
    }

    public function updateRole($id, $name, $isLock, $descr)
    {
        $sql = "SELECT a FROM Core:BaseRole a where a.id =:id ";
        $params = [];
        $params['id'] = $id;
        $model = $this->db()->fetchOne($sql, $params, 1);
        $model->setName($name);
        $model->setIsLock($isLock);
        $model->setDescr($descr);
        $this->db()->save($model);
    }

    public function checkName($name, $id = 0)
    {
        $sql = "SELECT a FROM Core:BaseRole a where a.name =:name ";
        $params = [];
        $params['name'] = $name;
        if ($id) {
            $sql = $sql . " AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->db()->fetchOne($sql, $params);
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Core:BaseRole a where a.id =:id ";
        $params = [];
        $params['id'] = $id;
        return $this->db()->fetchOne($sql, $params);
    }

    public function deleteRole($id)
    {
        $sql = "SELECT a FROM Core:BaseRole a where a.id =:id ";
        $params = [];
        $params['id'] = $id;
        $model = $this->db()->fetchOne($sql, $params, 1);
        $this->db()->delete($model);
        return true;
    }

    public function bindMenu($roleId, $menuIds)
    {
        //先删除
        $sql = "SELECT a FROM Core:BaseRoleMenu a WHERE a.roleId=:roleId";
        $models = $this->db()->fetchAll($sql, ['roleId' => $roleId], 1);
        $this->db()->hardDelete($models);
        if ($menuIds) {
            foreach ($menuIds as $menuId) {
                $model = new BaseRoleMenu();
                $model->setMenuId($menuId);
                $model->setRoleId($roleId);
                $this->db()->save($model);
            }
        }
        return true;
    }

    public function getRoleMenu($roleId)
    {
        $sql = "SELECT a.menuId FROM Core:BaseRoleMenu a WHERE a.roleId=:roleId";
        $menuIds = $this->db()->fetchFields("menuId", $sql, ['roleId' => $roleId]);
        return $menuIds;
    }

    public function getAllRole()
    {
        $sql = "SELECT a FROM Core:BaseRole a";
        return $this->db()->fetchAll($sql);
    }
}
