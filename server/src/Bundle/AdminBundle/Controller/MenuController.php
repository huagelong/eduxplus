<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Service\MenuService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;

class MenuController extends BaseAdminController
{

    /**
     * @Rest\Get("/menu/index", name="admin_menu_index")
     */
    public function indexAction( MenuService $menuService){

        $data = [];
        $data['allMenu'] = $menuService->getAllMenu();
        $data['menuSelect'] = $menuService->menuSelect();
        $data['adminRoute'] = $menuService->getAdminRoute();
        return $this->render("@AdminBundle/menu/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/menu/edit/{id}", name="admin_menu_edit")
     */
    public function editAction($id, MenuService $menuService){
        $detail = $menuService->getMenuById($id);
        $data = [];
        $data['menuSelect'] = $menuService->menuSelect();
        $data['adminRoute'] = $menuService->getAdminRoute(1);
        $data['detail'] = $detail;
        return $this->render("@AdminBundle/menu/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/menu/editDo/{id}", name="admin_api_menu_edit")
     */
    public function editDoAction($id, Request $request, MenuService $menuService){
        $pid = (int) $request->get("parentId");
        $name = $request->get("title");
        $sort = (int) $request->get("sort");
        $style = $request->get("icon");
        $uri = $request->get("uri");
        $isLock = $request->get("isLock");
        $isAccess = $request->get("isAccess");
        $isShow = $request->get("isShow");
        $descr = $request->get("descr");

        $isLock = $isLock=="on"?1:0;
        $isAccess = $isAccess=="on"?1:0;
        $isShow = $isShow=="on"?1:0;

        if(!$name) return $this->responseError("标题不能为空!");
        if(mb_strlen($name, 'utf-8')>20) return $this->responseError("标题不能大于20字!");

        if($menuService->checkMenuName($name, $id)){
            return $this->responseError("标题已存在!");
        }

        if($descr){
            if(mb_strlen($descr, 'utf-8')>20) return $this->responseError("描述不能大于20字!");
        }

        $menuService->editMenu($id, $name, $descr, $pid, $uri, $style,$sort, $isLock, $isAccess, $isShow);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseSuccess("操作成功!");
    }

    /**
     * @Rest\Post("/api/menu/deleteDo/{id}", name="admin_api_menu_delete")
     */
    public function deleteAction($id, MenuService $menuService){
        $menuService->deleteMenuById($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_menu_index"));
    }

    /**
     * @Rest\Post("/api/menu/addDo", name="admin_api_menu_add")
     */
    public function addAction(Request $request, MenuService $menuService){
        $pid = (int) $request->get("parentId");
        $name = $request->get("title");
        $sort = (int) $request->get("sort");
        $style = $request->get("icon");
        $uri = $request->get("uri");
        $isLock = $request->get("isLock");
        $isAccess = $request->get("isAccess");
        $isShow = $request->get("isShow");
        $descr = $request->get("descr");

        $isLock = $isLock=="on"?1:0;
        $isAccess = $isAccess=="on"?1:0;
        $isShow = $isShow=="on"?1:0;

        if($isShow){
            $parent = $menuService->getMenuById($pid);
            if(!$parent) return $this->responseError("父菜单不存在!");
            if($parent['pid'] != 0) return $this->responseError("显示的菜单不能超过二级!");
        }

        if(!$name) return $this->responseError("标题不能为空!");
        if(mb_strlen($name, 'utf-8')>20) return $this->responseError("标题不能大于20字!");

        if($menuService->checkMenuName($name)){
            return $this->responseError("标题已存在!");
        }

        if($descr){
            if(mb_strlen($descr, 'utf-8')>20) return $this->responseError("描述不能大于20字!");
        }

        $menuService->addMenu($name, $descr, $pid, $uri, $style, $sort, $isLock, $isAccess, $isShow);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_menu_index"));
    }

    /**
     * @Rest\Post("/api/menu/updateSortDo", name="admin_api_menu_updateSort")
     */
    public function updateSort(Request $request, MenuService $menuService){
        $data = $request->request->all();
        $menuService->updateSort($data);
        return $this->responseSuccess("更新排序成功!", $this->generateUrl("admin_menu_index"));
    }
}
