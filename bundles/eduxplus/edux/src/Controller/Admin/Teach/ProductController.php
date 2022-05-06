<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 09:57
 */

namespace Eduxplus\EduxBundle\Controller\Admin\Teach;

use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\EduxBundle\Service\Teach\ProductService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class ProductController extends BaseAdminController
{

    
    public function indexAction(Request $request, Grid $grid, ProductService $productService, CategoryService $categoryService, UserService $userService)
    {

        $select = $categoryService->categorySelect();

        $pageSize = 40;
        $grid->setListService($productService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("产品名称")->field("name")->sort("a.name");
        $grid->text("类别")->field("category")->sort("a.category");
        $grid->text("自动分班最大班级人数")->field("maxMemberNumber");
        $grid->text("创建人")->field("creater");
        $grid->boole2("上架？")->field("status")->actionCall("admin_api_teach_product_switchStatus", function ($obj) use($productService) {
            $id = $productService->getPro($obj, "id");
            $defaultValue = $productService->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_teach_product_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->text("类别")->field("category");
        $grid->text("协议")->field("agreement");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");


        $grid->setTableAction('admin_teach_studyplan_index', function ($obj) use($productService){
            $id = $productService->getPro($obj, "id");
            $url = $this->generateUrl('admin_teach_studyplan_index', ['id' => $id]);
            $str = '<a href="javascript:;" data-url=' . $url . ' title="开课计划" class=" btn btn-info btn-xs newTab"><i class="mdi mdi-folder-clock"></i>开课计划</a>';
            return  $str;
        });

        $grid->setTableAction('admin_teach_product_edit', function ($obj) use($productService) {
            $id = $productService->getPro($obj, "id");
            $url = $this->generateUrl('admin_teach_product_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_teach_product_delete', function ($obj) use($productService) {
            $id = $productService->getPro($obj, "id");
            $url = $this->generateUrl('admin_api_teach_product_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });

        //批量删除
        $grid->setBathDelete("admin_api_teach_product_bathdelete");

        $grid->gbAddButton("admin_teach_product_add");
        //搜索
        $grid->snumber("ID")->field("a.id");
        $grid->stext("名称")->field("a.name");
        $grid->sselect("类别")->field("a.categoryId")->options($select);
        $grid->sselect("上架？")->field("a.status")->options(["全部" => -1, "下架" => 0, "上架" => 1]);
        $grid->ssearchselect("创建人")->field("a.createUid")->options(function () use ($request, $userService) {
            $values = $request->get("values");
            $createUid = ($values && isset($values["a.createUid"])) ? $values["a.createUid"] : 0;
            if ($createUid) {
                $users = $userService->searchResult($createUid);
            } else {
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchAdminUserDo"), $users];
        });
        $grid->sdaterange("创建时间")->field("a.createdAt");
        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    
    public function addAction(Form $form, ProductService $productService, CategoryService $categoryService)
    {

        $form->text("产品名称")->field("name")->isRequire(1);
        $form->select("协议")->field("agreementId")->isRequire(1)->options($productService->getAgreements());
        $form->select("类目")->field("categoryId")->isRequire(1)->options($categoryService->categorySelect());
        $form->boole("上架？")->field("status")->isRequire(1);
        $form->text("自动分班最大班级人数")->field("maxMemberNumber")->isRequire(1)->placeholder("请输入整数");
        $form->textarea("简介")->field("descr");

        $formData = $form->create($this->generateUrl("admin_api_teach_product_add"));
        return $this->content()->title("添加产品")
               ->breadcrumb("产品管理", "admin_teach_product_index")
               ->renderAdd($formData);
    }

    
    public function addDoAction(Request $request, ProductService $productService)
    {
        $name = $request->get("name");
        $agreementId = (int) $request->get("agreementId");
        $categoryId = (int) $request->get("categoryId");
        $descr = $request->get("descr");
        $status = $request->get("status");
        $maxMemberNumber = (int) $request->get("maxMemberNumber");
        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("产品名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("产品名称不能大于50字!");
        if ($categoryId <= 0) return $this->responseError("请选择分类!");
        $uid = $this->getUid();
        $productService->add($uid, $name, $agreementId, $status, $maxMemberNumber, $categoryId, $descr);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_teach_product_index"));
    }

    
    public function editAction($id, Form $form, ProductService $productService,  CategoryService $categoryService)
    {
        $info = $productService->getById($id);


        $form->text("产品名称")->field("name")->isRequire(1)->defaultValue($info['name']);
        $form->select("协议")->field("agreementId")->isRequire(1)->options($productService->getAgreements())->defaultValue($info['agreementId']);
        $form->select("类目")->field("categoryId")->isRequire(1)->options($categoryService->categorySelect())->defaultValue($info['categoryId']);
        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);
        $form->text("自动分班最大班级人数")->field("maxMemberNumber")->isRequire(1)->placeholder("请输入整数")->defaultValue($info['maxMemberNumber']);
        $form->textarea("简介")->field("descr")->defaultValue($info['descr']);

        $formData = $form->create($this->generateUrl("admin_api_teach_product_edit", ['id' => $id]));
        return $this->content()->renderEdit($formData);
    }

    
    public function editDoAction($id, Request $request, ProductService $productService)
    {
        $name = $request->get("name");
        $agreementId = (int) $request->get("agreementId");
        $categoryId = (int) $request->get("categoryId");
        $descr = $request->get("descr");
        $status = $request->get("status");
        $maxMemberNumber = (int) $request->get("maxMemberNumber");
        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("产品名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("产品名称不能大于50字!");
        if ($categoryId <= 0) return $this->responseError("请选择分类!");
        $uid = $this->getUid();
        $productService->edit($id, $name, $agreementId, $status, $maxMemberNumber, $categoryId, $descr);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_teach_product_index"));
    }

    
    public function deleteDoAction($id, ProductService $productService)
    {
        $productService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_product_index"));
    }

    
    public function bathdeleteDoAction(Request $request, ProductService $productService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $productService->del($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_product_index"));
    }

    
    public function switchStatusAction($id, ProductService $productService, Request $request)
    {
        $state = (int) $request->get("state");
        $productService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }
}
