<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/28 16:56
 */

namespace Eduxplus\CmsBundle\Controller\Admin;


use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\CmsBundle\Service\PageService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PageController extends BaseAdminController
{
    
     public function indexAction( Request $request,Grid $grid, PageService $pageService){
         $pageSize = 40;
         $grid->setListService($pageService, "getList");
         $grid->text("ID")->field("id")->sort("a.id");
         $grid->text("单页名称")->field("name");
         $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("admin_api_mall_page_switchStatus", function ($obj) use($pageService) {
             $id = $pageService->getPro($obj, "id");
             $defaultValue = $pageService->getPro($obj, "status");
             $url = $this->generateUrl('admin_api_mall_page_switchStatus', ['id' => $id]);
             $checkStr = $defaultValue ? "checked" : "";
             $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
             $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
             return $str;
         });
         $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

         //添加
         $grid->gbAddButton("admin_mall_page_add");
         //搜索
         $grid->snumber("ID")->field("a.id");
         $grid->stext("单页名称")->field("a.name");

         $grid->viewAction("admin_mall_page_view")
             ->editAction("admin_mall_page_edit")
             ->deleteAction("admin_api_mall_page_delete");

         //批量删除
         $grid->setBathDelete("admin_api_mall_page_bathdelete");

         return $this->content()->renderList($grid->create($request, $pageSize));
     }

    
    public function addAction(Form $form) {
        $form->text("单页名称")->field("name")->isRequire(1);
        $form->richEditor("内容")->field("content")->isRequire(1);
        $form->boole("上架？")->field("status")->isRequire(1);

        $formData = $form->create($this->generateUrl("admin_api_mall_page_add"));
        return $this->content()->title("添加单页")
                ->breadcrumb("单页管理", "admin_mall_page_index")
                ->renderAdd($formData);
    }


    
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


    
    public function viewAction($id, View $view, PageService $pageService) {
        $info = $pageService->getById($id);

        $view->text("单页名称")->field("name")->defaultValue($info['name']);
        $view->richEditor("内容")->field("content")->defaultValue($info['main']['content']);;
        $view->boole("上架？")->field("status")->defaultValue($info['status']);

        $formData = $view->create();
        return $this->content()->renderView($formData);
    }

    
    public function editAction($id, Form $form, PageService $pageService) {
        $info = $pageService->getById($id);

        $form->text("单页名称")->field("name")->isRequire(1)->defaultValue($info['name']);
        $form->richEditor("内容")->field("content")->isRequire(1)->defaultValue($info['main']['content']);;
        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);

        $formData = $form->create($this->generateUrl("admin_api_mall_page_edit", ["id"=>$id]));
        return $this->content()->renderEdit($formData);
    }

    
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


    
    public function deleteDoAction($id, PageService $pageService)
    {
        $pageService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_page_index"));
    }
    
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
    
    public function switchStatusAction($id, PageService $pageService, Request $request)
    {
        $state = (int) $request->get("state");
        $pageService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

}
