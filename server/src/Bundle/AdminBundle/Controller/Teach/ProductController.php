<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 09:57
 */

namespace App\Bundle\AdminBundle\Controller\Teach;

use App\Bundle\AdminBundle\Service\Teach\CategoryService;
use App\Bundle\AdminBundle\Service\Teach\ProductService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class ProductController extends BaseAdminController
{

    /**
     * @Rest\Get("/teach/product/index", name="admin_teach_product_index")
     */
    public function indexAction(Request $request, Grid $grid,ProductService $productService,CategoryService $categoryService){

        $select = $categoryService->categorySelect();

        $pageSize = 20;
        $grid->setListService($productService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("产品名称", "text", "name","a.name");
        $grid->setTableColumn("类别", "text", "category");
        $grid->setTableActionColumn("admin_api_teach_product_switchPlanAutoStatus", "自动更新学习计划", "boole2", "studyPlanAuto", null,null,function($obj){
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "studyPlanAuto");
            $url = $this->generateUrl('admin_api_teach_product_switchPlanAutoStatus', ['id' => $id]);
            $checkStr = $defaultValue?"checked":"";
            $confirmStr = $defaultValue? "确认要关闭吗？":"确认要开启吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->setTableColumn("自动分班最大班级人数", "text", "maxMemberNumber");
        $grid->setTableColumn("创建人", "text", "creater");
        $grid->setTableActionColumn("admin_api_teach_product_switchStatus", "是否上架", "boole2", "status", null,null,function($obj){
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_teach_product_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue?"checked":"";
            $confirmStr = $defaultValue? "确认要下架吗？":"确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->setTableColumn("类别", "text", "category");
        $grid->setTableColumn("协议", "text", "agreement");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        $grid->setTableAction('admin_teach_chapter_index', function($obj){
            $id = $obj['id'];
            $url = $this->generateUrl('admin_teach_chapter_index',['id'=>$id]);
            $str = '<a href='.$url.' data-width="1000px" title="学习计划管理" class=" btn btn-info btn-xs"><i class="fa fa-list"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_teach_product_edit', function($obj){
            $id = $obj['id'];
            $url = $this->generateUrl('admin_teach_product_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_teach_product_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_api_teach_product_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $grid->setGridBar("admin_teach_product_add","添加", $this->generateUrl("admin_teach_product_add"), "fas fa-plus", "btn-success");

        //搜索
        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("名称", "text", "a.name");
        $grid->setSearchField("类别", 'select', 'a.categoryId' , function()use($select){
            return $select;
        });
        $grid->setSearchField("是否上架", "select", "a.status", function(){
            return ["全部"=>-1,"下架"=>0, "上架"=>1];
        });

        $grid->setSearchField("创建人", "search_select", "a.createUid",function(){
            return $this->generateUrl("admin_api_teach_product_searchUserDo");
        });
        $grid->setSearchField("创建时间", "daterange", "a.createdAt");

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/teach/product/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/teach/product/add", name="admin_teach_product_add")
     */
    public function addAction(Form $form, ProductService $productService, CategoryService $categoryService){

        $form->setFormField("产品名称", 'text', 'name' ,1);
        $form->setFormField("协议", 'select', 'agreementId' ,1, "", function()use($productService){
            return $productService->getAgreements();
        });

        $form->setFormField("类目", 'select', 'categoryId', 1, "", function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });
        $form->setFormField("是否上架", 'boole', 'status', 1);
        $form->setFormField("自动更新学习计划", 'boole', 'studyPlanAuto', 1);
        $form->setFormField("自动分班最大班级人数", 'text', 'maxMemberNumber' ,1, "","", "请输入整数");
        $form->setFormField("简介", 'textarea', 'descr');

        $formData = $form->create($this->generateUrl("admin_api_teach_product_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/teach/product/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/product/addDo", name="admin_api_teach_product_add")
     */
    public function addDoAction(Request $request, ProductService $productService){
        $name = $request->get("name");
        $agreementId = (int) $request->get("agreementId");
        $categoryId = (int) $request->get("categoryId");
        $descr = $request->get("descr");
        $status = $request->get("status");
        $studyPlanAuto = $request->get("studyPlanAuto");
        $maxMemberNumber = (int) $request->get("maxMemberNumber");
        $status = $status=="on"?1:0;
        $studyPlanAuto = $studyPlanAuto=="on"?1:0;

        if(!$name) return $this->responseError("产品名称不能为空!");
        if(mb_strlen($name, 'utf-8')>50) return $this->responseError("产品名称不能大于50字!");
        if($categoryId <=0) return $this->responseError("请选择分类!");
        $uid = $this->getUid();
        $productService->add($uid, $name, $agreementId, $status, $maxMemberNumber, $categoryId, $studyPlanAuto, $descr);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_teach_product_index"));
    }

    /**
     * @Rest\Get("/teach/product/edit/{id}", name="admin_teach_product_edit")
     */
    public function editAction($id, Form $form, ProductService $productService,  CategoryService $categoryService){
        $info = $productService->getById($id);

        $form->setFormField("产品名称", 'text', 'name' ,1, $info['name']);
        $form->setFormField("协议", 'select', 'agreementId' ,1, $info['agreementId'], function()use($productService){
            return $productService->getAgreements();
        });

        $form->setFormField("类目", 'select', 'categoryId', 1, $info['categoryId'], function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });
        $form->setFormField("是否上架", 'boole', 'status', 1, $info['status']);
        $form->setFormField("自动更新学习计划", 'boole', 'studyPlanAuto', 1, $info['studyPlanAuto']);
        $form->setFormField("自动分班最大班级人数", 'text', 'maxMemberNumber' ,1,$info['maxMemberNumber'],"", "请输入整数");
        $form->setFormField("简介", 'textarea', 'descr', 0, $info['descr']);

        $formData = $form->create($this->generateUrl("admin_api_teach_product_edit", ['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/teach/product/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/product/editDo/{id}", name="admin_api_teach_product_edit")
     */
    public function editDoAction($id, Request $request, ProductService $productService){
        $name = $request->get("name");
        $agreementId = (int) $request->get("agreementId");
        $categoryId = (int) $request->get("categoryId");
        $descr = $request->get("descr");
        $status = $request->get("status");
        $studyPlanAuto = $request->get("studyPlanAuto");
        $maxMemberNumber = (int) $request->get("maxMemberNumber");
        $status = $status=="on"?1:0;
        $studyPlanAuto = $studyPlanAuto=="on"?1:0;

        if(!$name) return $this->responseError("产品名称不能为空!");
        if(mb_strlen($name, 'utf-8')>50) return $this->responseError("产品名称不能大于50字!");
        if($categoryId <=0) return $this->responseError("请选择分类!");
        $uid = $this->getUid();
        $productService->edit($id, $name, $agreementId, $status, $maxMemberNumber, $categoryId, $studyPlanAuto, $descr);

        return $this->responseSuccess("编辑成功!", $this->generateUrl("admin_teach_product_index"));
    }

    /**
     * @Rest\Post("/api/teach/product/deleteDo/{id}", name="admin_api_teach_product_delete")
     */
    public function deleteDoAction($id, ProductService $productService){
        $productService->del($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_teach_product_index"));
    }

    /**
     * @Rest\Get("/api/teach/product/searchUserDo", name="admin_api_teach_product_searchUserDo")
     */
    public function searchUserDoAction(Request $request, ProductService $productService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $productService->searchAdminFullName($kw);
        return $data;
    }

    /**
     * @Rest\Post("/api/teach/product/switchStatusDo/{id}", name="admin_api_teach_product_switchStatus")
     */
    public function switchStatusAction($id, ProductService $productService, Request $request){
        $state = (int) $request->get("state");
        $productService->switchStatus($id, $state);
        return $this->responseSuccess("操作成功!");
    }

    /**
     * @Rest\Post("/api/teach/product/switchPlanAutoStatusDo/{id}", name="admin_api_teach_product_switchPlanAutoStatus")
     */
    public function switchPlanAutoStatusAction($id, ProductService $productService, Request $request){
        $state = (int) $request->get("state");
        $productService->switchPlanAutoStatus($id, $state);
        return $this->responseSuccess("操作成功!");
    }
}
