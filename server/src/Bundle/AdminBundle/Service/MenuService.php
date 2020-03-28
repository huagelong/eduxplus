<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/27 10:47
 */

namespace App\Bundle\AdminBundle\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use Symfony\Component\HttpFoundation\Request;

class MenuService extends BaseService
{


    public function getMyMenu($uid){
        //获取用户角色
        $dqlRole = "SELECT a.roleId FROM App:BaseRoleUser a WHERE a.uid=:uid";
        $roleIds = $this->fetchFieldsByDql("roleId", $dqlRole, ["uid"=>$uid]);
        if(!$roleIds) return [];
//        dump($roleIds);
        $roleIds = array_unique($roleIds);
        $dqlMenu = "SELECT a.menuId FROM App:BaseRoleMenu a WHERE a.roleId in(:roleId)";
        $menuIds = $this->fetchFieldsByDql("menuId", $dqlMenu, ["roleId"=>$roleIds]);
        if(!$menuIds) return [];
//        dump($menuIds);
        $menuIds = array_unique($menuIds);
        $menuListDql = "SELECT a FROM App:BaseMenu a WHERE a.id in(:id) ORDER BY a.sort ASC";
        $menulist = $this->fetchAllByDql($menuListDql, ["id"=>$menuIds]);
        if(!$menulist) return [];
//        dump($menulist);
        //处理
        $rs = [];
        foreach ($menulist as $v){
            $pid = $v->getPid();
            $rs[$pid][] = $v;
        }
        return $rs;
    }

    /**
     * 获取父类导航
     */
    public function getParentMenuId($route){
        $menuDql = "SELECT a.pid FROM App:BaseMenu a WHERE a.url =:url";
        return $this->fetchFieldByDql("pid", $menuDql, ["url"=>$route]);
    }

    public function getAllRoute(){
        $router = $this->get("router");
        $allRoute = $router->getRouteCollection();
        return $allRoute->all();
    }

}

