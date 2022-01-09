<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:01
 */

namespace Eduxplus\EduxBundle\Controller\Mall;

use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\EduxBundle\Service\Mall\HelpCategoryService;
use Eduxplus\EduxBundle\Service\Mall\HelpService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class HelpController extends BaseAdminController
{

    /**
     *
     * @Route("/mall/help/index", name="admin_mall_help_index")
     */
    public function indexAction(Request $request, Grid $grid, HelpService $helpService){
        $pageSize = 40;
        $grid->setListService($helpService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("名称")->field("name");
        $grid->text("分类")->field("categoryName");
        $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("admin_api_mall_help_switchStatus", function ($obj) use($helpService) {
            $id = $helpService->getPro($obj, "id");
            $defaultValue = $helpService->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_mall_help_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->text("排序")->field("sort");
        $grid->text("置顶")->field("topValue");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        //添加
        $grid->gbButton("添加")->route("admin_mall_help_add")
            ->url($this->generateUrl("admin_mall_help_add"))
            ->styleClass("btn-success")->iconClass("mdi mdi-plus");
        //搜索
        $grid->snumber("ID")->field("a.id");
        $grid->stext("名称")->field("a.name");

        //编辑等
        $grid->setTableAction('admin_mall_help_view', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_mall_help_view', ['id' => $id]);
            $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="mdi mdi-eye"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_mall_help_edit', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_mall_help_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_mall_help_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_api_mall_help_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });

        //批量删除
        $grid->setBathDelete("admin_api_mall_help_bathdelete");

        $data = [];

        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@EduxBundle/mall/help/index.html.twig", $data);
    }

    /**
     *
     * @Route("/mall/help/view/{id}", name="admin_mall_help_view")
     */
    public function viewAction($id, View $view, HelpService $helpService, HelpCategoryService $helpCategoryService){
        $info = $helpService->getById($id);
        $select = $helpCategoryService->categorySelect();

        $view->text("名称")->field("name")->defaultValue($info['name']);
        $view->select("帮助分类")->field("categoryId")->options($select)->defaultValue($info['categoryId']);
        $view->text("排序")->field("sort")->defaultValue($info['sort']);
        $view->text("置顶")->field("topValue")->defaultValue($info['topValue']);
        $view->richEditor("内容")->field("content")->defaultValue($info['main']['content']);;
        $view->boole("上架？")->field("status")->defaultValue($info['status']);

        $formData = $view->create();
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@EduxBundle/mall/help/view.html.twig", $data);
    }


    /**
     * @Route("/mall/help/add", name="admin_mall_help_add")
     */
    public function addAction(Form $form, HelpCategoryService $helpCategoryService)
    {
        $select = $helpCategoryService->categorySelect();
        $form->text("名称")->field("name")->isRequire(1);
        $form->select("帮助分类")->field("categoryId")->isRequire()->options($select);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue(0)->placeholder("数字越小越靠前");
        $form->text("置顶")->field("topValue")->isRequire(1)->defaultValue(0)->placeholder("大于0，表示置顶帮助");
        $form->richEditor("内容")->field("content")->isRequire(1);
        $form->boole("上架？")->field("status")->isRequire(1);

        $formData = $form->create($this->generateUrl("admin_api_mall_help_add"));
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@EduxBundle/mall/help/add.html.twig", $data);
    }

    /**
     * @Route("/mall/help/add/do", name="admin_api_mall_help_add")
     */
    public function addDoAction(Request $request, HelpService $helpService, HelpCategoryService $helpCategoryService)
    {
        $name = $request->get("name");
        $content  = $request->get("content");
        $status  = $request->get("status");
        $categoryId  = $request->get("categoryId");
        $sort = (int) $request->get("sort");
        $topValue =  (int) $request->get("topValue");

        $status = $status == "on" ? 1 : 0;

        if(!$name) return $this->responseError("名称不能为空!");
        if(!$content) return $this->responseError("内容不能为空!");
        if(!$categoryId) return $this->responseError("分类不能为空!");

        if(!mb_strlen($name, "utf-8")>100) return $this->responseError("名称不能大于100字!");
        $helpService->add($name, $content, $status, $categoryId, $sort, $topValue);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_help_index'));
    }

    /**
     * @Route("/mall/help/edit/{id}", name="admin_mall_help_edit")
     */
    public function editAction($id, Form $form, HelpService $helpService, HelpCategoryService $helpCategoryService)
    {
        $info = $helpService->getById($id);
        $select = $helpCategoryService->categorySelect();
        $form->text("名称")->field("name")->isRequire(1)->defaultValue($info['name']);
        $form->select("帮助分类")->field("categoryId")->isRequire()->options($select)->defaultValue($info['categoryId']);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue($info['sort']);
        $form->text("置顶")->field("topValue")->isRequire(1)->defaultValue($info['topValue']);
        $form->richEditor("内容")->field("content")->isRequire(1)->defaultValue($info['main']['content']);;
        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);

        $formData = $form->create($this->generateUrl("admin_api_mall_help_edit", ["id"=>$id]));
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@EduxBundle/mall/help/edit.html.twig", $data);
    }

    /**
     * @Route("/mall/help/edit/do/{id}", name="admin_api_mall_help_edit")
     */
    public function editDoAction($id, Request $request, HelpService $helpService)
    {
        $name = $request->get("name");
        $content  = $request->get("content");
        $status  = $request->get("status");
        $categoryId  = $request->get("categoryId");
        $sort = (int) $request->get("sort");
        $topValue =  (int) $request->get("topValue");

        $status = $status == "on" ? 1 : 0;

        if(!$name) return $this->responseError("名称不能为空!");
        if(!$content) return $this->responseError("内容不能为空!");
        if(!$categoryId) return $this->responseError("分类不能为空!");

        if(!mb_strlen($name, "utf-8")>100) return $this->responseError("名称不能大于100字!");
        $helpService->edit($id, $name, $content, $status, $categoryId, $sort, $topValue);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_help_index'));
    }

    /**
     * @Route("/mall/help/delete/do/{id}", name="admin_api_mall_help_delete")
     */
    public function deleteDoAction($id, HelpService $helpService)
    {
        $helpService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_help_index"));
    }

    /**
     *
     * @Route("/mall/help/bathdelete/do", name="admin_api_mall_help_bathdelete")
     */
    public function bathdeleteDoAction(Request $request, HelpService $helpService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $helpService->del($id);
            }
        }
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_help_index"));
    }



    /**
     * @Route("/mall/help/switchStatus/do/{id}", name="admin_api_mall_help_switchStatus")
     */
    public function switchStatusAction($id, HelpService $helpService, Request $request)
    {
        $state = (int) $request->get("state");
        $helpService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

}
