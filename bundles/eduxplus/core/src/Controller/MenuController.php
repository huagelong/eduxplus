<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\CoreBundle\Service\MenuService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends BaseAdminController
{

    /**
     * @Route("/menu/index", name="admin_menu_index")
     */
    public function indexAction(MenuService $menuService)
    {

        $data = [];
        $data['allMenu'] = $menuService->getAllMenu();
        $data['menuSelect'] = $menuService->menuSelect();
        $data['adminRoute'] = $menuService->getAdminRoute();
        return $this->render("@CoreBundle/menu/index.html.twig", $data);
    }

    /**
     * @Route("/menu/add", name="admin_menu_add")
     */
    public function addAction(MenuService $menuService)
    {
        $data = [];
        $data['menuSelect'] = $menuService->menuSelect();
        $data['adminRoute'] = $menuService->getAdminRoute();
        return $this->render("@CoreBundle/menu/add.html.twig", $data);
    }

    /**
     * @Route("/menu/edit/{id}", name="admin_menu_edit")
     */
    public function editAction($id, MenuService $menuService)
    {
        $detail = $menuService->getMenuById($id);
        $data = [];
        $data['menuSelect'] = $menuService->menuSelect();
        $data['adminRoute'] = $menuService->getAdminRoute(1);
        $data['detail'] = $detail;
        return $this->render("@CoreBundle/menu/edit.html.twig", $data);
    }

    /**
     * @Route("/menu/view/{id}", name="admin_menu_view")
     */
    public function viewAction($id, MenuService $menuService, View $view)
    {
        $detail = $menuService->getMenuById($id);
        $menuSelect = $menuService->menuSelect();
        $adminRoute = $menuService->getAdminRoute(1);
        $adminRouteTmp = [];
        if($adminRoute){
            foreach ($adminRoute as $k=>$v){
                $adminRouteTmp[$v] = $v;
            }
        }
//        $view->select("父级菜单")->field("pid")->defaultValue($detail["pid"])->options($menuSelect);
        $view->text("标题")->field("title")->defaultValue($detail['name']);
        $view->text("排序")->field("sort")->defaultValue($detail['sort']);
        $view->text("图标")->field("style")->defaultValue($detail['style']);
        $view->select("路径")->field("url")->defaultValue($detail['url'])->options($adminRouteTmp);
        $view->boole("显示到导航")->field("isShow")->defaultValue($detail['isShow']);
        $view->boole("锁定")->field("isLock")->defaultValue($detail['isLock']);
        $view->boole("仅权限")->field("isAccess")->defaultValue($detail['isAccess']);
        $view->textarea("描述")->field("descr")->defaultValue($detail['descr']);

        $viewData = $view->create();
        return $this->content()->renderView($viewData);
    }

    /**
     * @Route("/menu/edit/do/{id}", name="admin_api_menu_edit")
     */
    public function editDoAction($id, Request $request, MenuService $menuService)
    {
        $pid = (int) $request->get("parentId");
        $name = $request->get("title");
        $sort = (int) $request->get("sort");
        $style = $request->get("icon");
        $uri = $request->get("uri");
        $isLock = $request->get("isLock");
        $isAccess = $request->get("isAccess");
        $isShow = $request->get("isShow");
        $descr = $request->get("descr");

        $isLock = $isLock == "on" ? 1 : 0;
        $isAccess = $isAccess == "on" ? 1 : 0;
        $isShow = $isShow == "on" ? 1 : 0;

        if (!$name) return $this->responseError("标题不能为空!");
        if (mb_strlen($name, 'utf-8') > 20) return $this->responseError("标题不能大于20字!");

        if ($menuService->checkMenuName($name, $id)) {
            return $this->responseError("标题已存在!");
        }

        if ($descr) {
            if (mb_strlen($descr, 'utf-8') > 20) return $this->responseError("描述不能大于20字!");
        }

        if(!$uri){
            return $this->responseError("路径不能为空!");
        }

        $menuService->editMenu($id, $name, $descr, $pid, $uri, $style, $sort, $isLock, $isAccess, $isShow);

        if ($this->error()->has()) {
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("操作成功!");
    }

    /**
     * @Route("/menu/delete/do/{id}", name="admin_api_menu_delete")
     */
    public function deleteAction($id, MenuService $menuService)
    {
        $child = $menuService->getChild($id);
        if ($child) return $this->responseError("请先删除子节点菜单");
        $menuService->deleteMenuById($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_menu_index"));
    }

    /**
     * @Route("/menu/add/do", name="admin_api_menu_add")
     */
    public function addDoAction(Request $request, MenuService $menuService)
    {
        $pid = (int) $request->get("parentId");
        $name = $request->get("title");
        $sort = (int) $request->get("sort");
        $style = $request->get("icon");
        $uri = $request->get("uri");
        $isLock = $request->get("isLock");
        $isAccess = $request->get("isAccess");
        $isShow = $request->get("isShow");
        $descr = $request->get("descr");

        $isLock = $isLock == "on" ? 1 : 0;
        $isAccess = $isAccess == "on" ? 1 : 0;
        $isShow = $isShow == "on" ? 1 : 0;

        if ($isShow) {
            if (!$menuService->checkMenuThree($pid)) return $this->responseError("显示的菜单不能超过三级!");
        }

        if (!$name) return $this->responseError("标题不能为空!");
        if (mb_strlen($name, 'utf-8') > 20) return $this->responseError("标题不能大于20字!");

        if ($menuService->checkMenuName($name)) {
            return $this->responseError("标题已存在!");
        }

        if(!$uri){
            return $this->responseError("路径不能为空!");
        }

        if ($descr) {
            if (mb_strlen($descr, 'utf-8') > 20) return $this->responseError("描述不能大于20字!");
        }

        $menuService->addMenu($name, $descr, $pid, $uri, $style, $sort, $isLock, $isAccess, $isShow);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_menu_index"));
    }

    /**
     * @Route("/menu/updateSort/do", name="admin_api_menu_updateSort")
     */
    public function updateSort(Request $request, MenuService $menuService)
    {
        $data = $request->request->all();
        $menuService->updateSort($data);
        return $this->responseMsgRedirect("更新排序成功!", $this->generateUrl("admin_menu_index"));
    }
}
