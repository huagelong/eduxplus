<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/28 16:56
 */

namespace App\Bundle\AdminBundle\Controller\Mall;


use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;
use App\Bundle\AdminBundle\Lib\View\View;
use App\Bundle\AdminBundle\Service\Mall\PageService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class PageController extends BaseAdminController
{
    /**
     *
     * @Rest\Get("/mall/page/index", name="admin_mall_page_index")
     */
     public function indexAction( Request $request,Grid $grid, PageService $pageService){
         $pageSize = 40;
         $grid->setListService($pageService, "getList");
         $grid->text("#")->field("id")->sort("a.id");
         $grid->text("单页名称")->field("name");
         $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("admin_api_mall_page_switchStatus", function ($obj) {
             $id = $this->getPro($obj, "id");
             $defaultValue = $this->getPro($obj, "status");
             $url = $this->generateUrl('admin_api_mall_page_switchStatus', ['id' => $id]);
             $checkStr = $defaultValue ? "checked" : "";
             $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
             $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
             return $str;
         });
         $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

         //添加
         $grid->gbButton("添加")->route("admin_mall_page_add")
             ->url($this->generateUrl("admin_mall_page_add"))
             ->styleClass("btn-success")->iconClass("fas fa-plus");
         //搜索
         $grid->snumber("ID")->field("a.id");
         $grid->stext("单页名称")->field("a.name");

         //编辑等
         $grid->setTableAction('admin_mall_page_view', function ($obj) {
             $id = $obj['id'];
             $url = $this->generateUrl('admin_mall_page_view', ['id' => $id]);
             $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="fas fa-eye"></i></a>';
             return  $str;
         });

         $grid->setTableAction('admin_mall_page_edit', function ($obj) {
             $id = $obj['id'];
             $url = $this->generateUrl('admin_mall_page_edit', ['id' => $id]);
             $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
             return  $str;
         });

         $grid->setTableAction('admin_api_mall_page_delete', function ($obj) {
             $id = $obj['id'];
             $url = $this->generateUrl('admin_api_mall_page_delete', ['id' => $id]);
             return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
         });

         //批量删除
         $bathDelUrl = $this->genUrl("admin_api_mall_page_bathdelete");
         $grid->setBathDelete("admin_api_mall_page_bathdelete", $bathDelUrl);

         $data = [];

         $data['list'] = $grid->create($request, $pageSize);

         return $this->render("@AdminBundle/mall/page/index.html.twig", $data);
     }

    /**
     *
     * @Rest\Get("/mall/page/add", name="admin_mall_page_add")
     */
    public function addAction(Form $form) {
        $form->text("单页名称")->field("name")->isRequire(1);
        $form->richEditor("内容")->field("content")->isRequire(1);
        $form->boole("上架？")->field("status")->isRequire(1);

        $formData = $form->create($this->generateUrl("admin_api_mall_page_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/mall/page/add.html.twig", $data);
    }


    /**
     *
     * @Rest\Post("/mall/page/add/do", name="admin_api_mall_page_add")
     */
    public function addDoAction(Request $request, PageService $pageService)
    {
        $name = $request->get("name");
        $content  = $request->get("content");
        $status  = $request->get("status");

        $status = $status == "on" ? 1 : 0;

        if(!$name) return $this->responseError("单页名称不能为空!");
        if(!$content) return $this->responseError("单页内容不能为空!");

        if(!mb_strlen($name, "utf-8")>40) return $this->responseError("单页名称不能大于40字!");
        $pageService->add($name, $content, $status);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_page_index'));
    }


    /**
     *
     * @Rest\Get("/mall/page/view/{id}", name="admin_mall_page_view")
     */
    public function viewAction($id, View $view, PageService $pageService) {
        $info = $pageService->getById($id);

        $view->text("单页名称")->field("name")->defaultValue($info['name']);
        $view->richEditor("内容")->field("content")->defaultValue($info['main']['content']);;
        $view->boole("上架？")->field("status")->defaultValue($info['status']);

        $formData = $view->create();
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/mall/page/view.html.twig", $data);
    }

    /**
     *
     * @Rest\Get("/mall/page/edit/{id}", name="admin_mall_page_edit")
     */
    public function editAction($id, Form $form, PageService $pageService) {
        $info = $pageService->getById($id);

        $form->text("单页名称")->field("name")->isRequire(1)->defaultValue($info['name']);
        $form->richEditor("内容")->field("content")->isRequire(1)->defaultValue($info['main']['content']);;
        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);

        $formData = $form->create($this->generateUrl("admin_api_mall_page_edit", ["id"=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/mall/page/edit.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/mall/page/edit/do/{id}", name="admin_api_mall_page_edit")
     */
    public function editDoAction($id, Request $request, PageService $pageService)
    {
        $name = $request->get("name");
        $content  = $request->get("content");
        $status  = $request->get("status");

        $status = $status == "on" ? 1 : 0;

        if(!$name) return $this->responseError("单页名称不能为空!");
        if(!$content) return $this->responseError("单页内容不能为空!");

        if(!mb_strlen($name, "utf-8")>40) return $this->responseError("单页名称不能大于40字!");
        $pageService->edit($id, $name, $content, $status);

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_page_index'));
    }


    /**
     *
     * @Rest\Post("/mall/page/delete/do/{id}", name="admin_api_mall_page_delete")
     */
    public function deleteDoAction($id, PageService $pageService)
    {
        $pageService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_page_index"));
    }


    /**
     *
     * @Rest\Post("/mall/page/bathdelete/do", name="admin_api_mall_page_bathdelete")
     */
    public function bathDeleteDoAction(Request $request, PageService $pageService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $pageService->del($id);
            }
        }
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_page_index"));
    }


    /**
     * @Rest\Post("/mall/page/switchStatus/do/{id}", name="admin_api_mall_page_switchStatus")
     */
    public function switchStatusAction($id, PageService $pageService, Request $request)
    {
        $state = (int) $request->get("state");
        $pageService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

}
