<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/27 10:47
 */

namespace App\Bundle\AdminBundle\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\BaseMenu;
use App\Repository\BaseMenuRepository;
use Symfony\Component\HttpFoundation\Request;

class MenuService extends BaseService
{


    public function getAllMenu(){
        $menuListDql = "SELECT a FROM App:BaseMenu a ORDER BY a.sort ASC";
        $menulist = $this->fetchAll($menuListDql);
        if(!$menulist) return [];

        $rs = [];
        foreach ($menulist as $v){
            $pid = $v['pid'];
            $rs[$pid][] = $v;
        }
        return $rs;
    }

    public function menuSelect(){
        $allMenu = $this->getAllMenu();
        $rs = [];
        if($allMenu[0]){
            foreach ($allMenu[0] as $vv){
                $id= $vv['id'];
                $name = "┝&nbsp;".$vv['name'];
                $vv['name'] = $name;
                $rs[] = $vv;
                if(isset($allMenu[$id])){
                    $pre = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_menuSelect($allMenu[$id], $pre, $rs);
                }
            }
        }
        return $rs;
    }

    protected function _menuSelect($menuArr, $pre="", &$rs){
        if($menuArr){
            foreach ($menuArr as $v){
                $id= $v['id'];
                $name = $pre."┝&nbsp;".$v['name'];
                $v['name'] = $name;
                $rs[] = $v;
                if(isset($menuArr[$id])){
                    $pre = $pre."&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_menuSelect($menuArr[$id], $pre, $rs);
                }
            }
        }
    }

    public function getMyMenuDefault($uid){
        $dqlRole = "SELECT a.roleId FROM App:BaseRoleUser a WHERE a.uid=:uid";
        $roleIds = $this->fetchFields("roleId", $dqlRole, ["uid"=>$uid]);
        if(!$roleIds) return [];
//        dump($roleIds);
        $roleIds = array_unique($roleIds);
        $dqlMenu = "SELECT a.menuId FROM App:BaseRoleMenu a WHERE a.roleId in(:roleId)";
        $menuIds = $this->fetchFields("menuId", $dqlMenu, ["roleId"=>$roleIds]);
        if(!$menuIds) return [];
//        dump($menuIds);
        $menuIds = array_unique($menuIds);
        $menuListDql = "SELECT a FROM App:BaseMenu a WHERE a.id in(:id) ORDER BY a.sort ASC";
        $menulist = $this->fetchAll($menuListDql, ["id"=>$menuIds]);
        if(!$menulist) return [];
        return $menulist;
    }

    public function getMyMenu($uid){
        //获取用户角色
        $menulist = $this->getMyMenuDefault($uid);
//        dump($menulist);
        //处理
        if(!$menulist) return [];
        $rs = [];
        foreach ($menulist as $v){
            $pid = $v['pid'];
            $rs[$pid][] = $v;
        }
        return $rs;
    }


    public function getMyMenuUrl($uid){
        $menulist = $this->getMyMenuDefault($uid);
        //处理
        if(!$menulist) return [];
        $result = [];
        foreach ($menulist as $v){
            $url = $v['url'];
            if($url) $result[] = $url;
        }
        return $result;
    }

    /**
     * 获取父类导航
     */
    public function getParentMenuId($route){
        $menuDql = "SELECT a.pid FROM App:BaseMenu a WHERE a.url =:url";
        return $this->fetchField("pid", $menuDql, ["url"=>$route]);
    }

    public function getAllRoute(){
        $router = $this->get("router");
        $allRoute = $router->getRouteCollection();
        return $allRoute->all();
    }

    public function getAdminRoute($noAccess=0){
        $allRout = $this->getAllRoute();
        if(!$allRout) return [];
        $rstmp = [];
        foreach ($allRout as $k=>$v){
            if(substr($k, 0, 6) == 'admin_'){
                if(!in_array($k, ['admin_login', 'admin_logout'])) $rstmp[]=$k;
            }
        }

        if(!$noAccess){
            $sql = "SELECT a.url FROM App:BaseMenu a WHERE a.url in(:url) ORDER BY a.id DESC";
            $rsExist = $this->fetchFields("url", $sql, ["url"=>$rstmp]);
            $rs  = array_diff($rstmp, $rsExist);
        }else{
            $rs = $rstmp;
        }
        return $rs;
    }


    public function checkMenuName($name,  $id=0){
        $sql = "SELECT a FROM App:BaseMenu a WHERE a.name =:name ";
        $params = [];
        $params['name'] = $name;
        if($id){
            $sql = $sql." AND a.id !=:id ";
            $params['id'] = $id;
        }
        return $this->fetchOne($sql, $params);
    }


    public function editMenu($id, $name, $descr, $pid, $uri, $style,$sort, $isLock, $isAccess, $isShow){
        $sql = "SELECT a FROM App:BaseMenu a WHERE a.id=:id";
        $menuModel = $this->fetchOne($sql, ['id'=>$id], 1);
        if(!$menuModel) return $this->error()->add("菜单不存在!");
        $menuModel->setName($name);
        if($descr) $menuModel->setDescr($descr);
        if($uri) $menuModel->setUrl($uri);
        $menuModel->setIsLock($isLock);
        $menuModel->setIsAccess($isAccess);
        $menuModel->setIsShow($isShow);
        $menuModel->setPid($pid);
        $menuModel->setSort($sort);
        if($style) $menuModel->setStyle($style);
        $menuId = $this->save($menuModel);
        return $menuId;
    }

    public function addMenu($name, $descr, $pid, $uri, $style,$sort, $isLock, $isAccess, $isShow){
        $menuModel = new BaseMenu();
        $menuModel->setName($name);
        if($descr) $menuModel->setDescr($descr);
        if($uri) $menuModel->setUrl($uri);
        $menuModel->setIsLock($isLock);
        $menuModel->setIsAccess($isAccess);
        $menuModel->setIsShow($isShow);
        $menuModel->setPid($pid);
        $menuModel->setSort($sort);
        if($style) $menuModel->setStyle($style);
        $menuId = $this->save($menuModel);
        return $menuId;
    }

    public function getMenuById($id){
        $sql = "SELECT a FROM App:BaseMenu a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id'=>$id]);
    }

    public function deleteMenuById($id){
        $sql = "SELECT a FROM App:BaseMenu a WHERE a.id=:id";
        $model= $this->fetchOne($sql, ['id'=>$id], 1);
        return $this->delete($model);
    }
}

