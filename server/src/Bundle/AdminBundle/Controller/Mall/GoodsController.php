<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 19:53
 */
namespace App\Bundle\AdminBundle\Controller\Mall;

use App\Bundle\AdminBundle\Service\Teach\CategoryService;
use App\Bundle\AdminBundle\Service\Mall\GoodsService;
use App\Bundle\AdminBundle\Service\Teach\ChapterService;
use App\Bundle\AdminBundle\Service\Teach\ProductService;
use App\Bundle\AdminBundle\Service\UserService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class GoodsController extends BaseAdminController
{

    /**
     *
     * @Rest\Get("/mall/goods/index", name="admin_mall_goods_index")
     */
    public function indexAction(Request $request,Grid $grid, GoodsService $goodsService,
                                CategoryService $categoryService, UserService $userService)
    {
        $pageSize = 20;
        $grid->setListService($goodsService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("商品名称", "text", "name");
        $grid->setTableColumn("品类", "text", "brand");
        $grid->setTableColumn("类目", "text", "category", "a.categoryId");
        $grid->setTableColumn("授课方式", "text", "teachingMethod", "a.teachingMethod", [1=>"面授",2=>"直播", 3=>"录播", 4=>"直播+面授", 5=>"直播+录播", 6=>"录播+面授", 7=>"直播+录播+面授"]);
        $grid->setTableColumn("课时", "text", "courseHour");
        $grid->setTableColumn("课次", "text", "courseCount");
        $grid->setTableColumn("成本价", "text", "marketPrice");
        $grid->setTableColumn("售价", "text", "shopPrice");
        $grid->setTableColumn("真实购买人数", "text", "buyNumber");
        $grid->setTableColumn("详情页海报图", "image", "goodsImg");
        $grid->setTableColumn("缩略图", "image", "goodsSmallImg");
        $grid->setTableActionColumn("admin_api_mall_goods_switchStatus", "是否上架", "boole2", "status", "a.status",null,function($obj){
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_mall_goods_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue?"checked":"";
            $confirmStr = $defaultValue? "确认要下架吗？":"确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->setTableColumn("创建人", "text", "creater", "a.createUid");
        $grid->setTableColumn("是否是组合商品", "boole", "isGroup", "a.isGroup");
        $grid->setTableColumn("组合商品类型", "text", "groupType", "a.groupType", [0=>"-", 1=>"可选", 2=>"全选"]);
        $grid->setTableColumn("协议", "text", "agreement", "a.agreementId");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        $grid->setTableAction('admin_mall_goods_edit', function($obj){
            $id = $obj['id'];
            $url = $this->generateUrl('admin_mall_goods_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_mall_goods_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_api_mall_goods_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $grid->setGridBar("admin_mall_goods_add","添加", $this->generateUrl("admin_mall_goods_add"), "fas fa-plus", "btn-success");

        //搜索
        $select = $categoryService->categorySelect();

        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("商品名称", "text", "a.name");
        $grid->setSearchField("授课方式", "select", "a.teachingMethod", function(){
            return ["全部"=>-1,"面授"=>1, "直播"=>2, "录播"=>3, "直播+面授"=>4, "直播+录播"=>5, "录播+面授"=>6, "直播+录播+面授"=>7];
        });

        $grid->setSearchField("是否上架", "select", "a.status", function(){
            return ["全部"=>-1,"下架"=>0, "上架"=>1];
        });

        $grid->setSearchField("创建人", "search_select", "a.createUid", function()use($request, $userService){
            $values = $request->get("values");
            $createUid = ($values&&isset($values["a.createUid"]))?$values["a.createUid"]:0;
            if($createUid){
                $users = $userService->searchResult($createUid);
            }else{
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchUserDo"),$users];
        });

        $grid->setSearchField("类别", 'select', 'a.categoryId' , function()use($select){
            return $select;
        });


        $grid->setSearchField("创建时间", "daterange", "a.createdAt");


        $data = [];

        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@AdminBundle/mall/goods/index.html.twig", $data);
    }

    /**
     *
     * @Rest\Get("/mall/goods/add", name="admin_mall_goods_add")
     */
    public function addAction(Form $form, ProductService $productService,
                               CategoryService $categoryService,
                              ChapterService $chapterService
    )
    {
        $form->setFormField("商品名称", 'text', 'name', 1);
        $form->setFormField("副标题", 'text', 'subhead');

        $form->setFormField("产品", 'search_select', 'productId', 0, "", function (){
            return [$this->generateUrl("admin_api_glob_searchProductDo"),[]];
        });

        $form->setFormField("组合商品子商品", 'search_multiple_select', 'goodsId[]', 0, "", function (){
            return [$this->generateUrl("admin_api_glob_searchGoodsDo"),[]];
        });

        $form->setFormField("组合商品购买方式", 'select', 'groupType' ,0, "", function(){
            return ["请选择"=>0,"子商品可选择"=>1, "子商品不可选择"=>2];
        });

        $form->setFormField("类目", 'select', 'categoryId', 1, "", function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });

        $form->setFormField("授课方式", 'select', 'teachingMethod' ,1, "", function(){
            return ["面授"=>1, "直播"=>2, "录播"=>3, "直播+面授"=>4, "直播+录播"=>5, "录播+面授"=>6, "直播+录播+面授"=>7];
        });

        $form->setFormField("上课老师", 'multiSelect', 'teachers[]', 1, "", function () use ($chapterService) {
            return $chapterService->getTeachers();
        });

        $form->setFormField("课时", 'text', 'courseHour', 1);
        $form->setFormField("课次", 'text', 'courseCount', 1);
        $form->setFormField("成本价", 'text', 'marketPrice', 1);
        $form->setFormField("售价", 'text', 'shopPrice');
        $form->setFormField("购买人数", 'text', 'buyNumberFalse', 1);
        $form->setFormField("购买协议", 'select', 'agreementId' ,1, "", function()use($productService){
            return $productService->getAgreements();
        });

        $options= [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_detail_goods"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024*50;//50m
        $options["data-required"] = 1;
        $form->setFormAdvanceField("详情页海报图", "file", 'goodsImg' , $options);

        $options= [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_small_goods"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024*50;//50m
        $options["data-required"] = 1;
        $form->setFormAdvanceField("缩略图", "file", 'goodsSmallImg' , $options);

        $form->setFormField("是否上架", 'boole', 'status', 1);
        $form->setFormField("排序", 'text', 'sort', 1, 0);

        $formData = $form->create($this->generateUrl("admin_api_mall_goods_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/mall/goods/add.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/api/mall/goods/addDo", name="admin_api_mall_goods_add")
     */
    public function addDoAction(Request $request, GoodsService $goodsService)
    {
        $name = $request->get("name");
        $productId = $request->get("productId");
        $goodsId = $request->get("goodsId");
        $categoryId = $request->get("categoryId");
        $subhead = $request->get("subhead");
        $teachingMethod = (int) $request->get("teachingMethod");
        $teachers = $request->get("teachers");
        $courseHour = $request->get("courseHour");
        $courseCount = $request->get("courseCount");
        $marketPrice = $request->get("marketPrice");
        $shopPrice = $request->get("shopPrice");
        $buyNumberFalse = (int)  $request->get("buyNumberFalse");
        $goodsImg = $request->get("goodsImg");
        $goodsSmallImg = $request->get("goodsSmallImg");
        $status = $request->get("status");
        $sort = (int) $request->get("sort");
        $agreementId = (int) $request->get("agreementId");
        $groupType =  (int) $request->get("groupType");

        $status = $status == "on" ? 1 : 0;

        if (! $name) return $this->responseError("商品名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("商品名称不能大于50字!");

        if($goodsService->checkName($name)) return $this->responseError("商品名称已存在!");

        if(!$productId && !$goodsId) return $this->responseError("产品或者商品必须选其一!");

        if($goodsId){
            if($productId) return $this->responseError("组合商品不能添加产品!");
        }else{
            if(!$shopPrice) return $this->responseError("非组合商品商品售价不能为空!");
        }

        if($subhead){
            if (mb_strlen($subhead, 'utf-8') > 80) return $this->responseError("副标题不能大于80字!");
        }

        if(!$teachers){
            return $this->responseError("老师不能为空!");
        }

        $uid = $this->getUid();
        $goodsService->add($uid, $name, $productId, $goodsId, $categoryId, $subhead,
            $teachingMethod, $teachers, $courseHour, $courseCount,
            $marketPrice,$shopPrice,$buyNumberFalse, $goodsImg, $goodsSmallImg, $status, $sort,$agreementId, $groupType);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_mall_goods_index'));
    }

    /**
     *
     * @Rest\Get("/mall/goods/edit/{id}", name="admin_mall_goods_edit")
     */
    public function editAction($id, Form $form, GoodsService $goodsService,
                               CategoryService $categoryService, ChapterService $chapterService,
                                ProductService $productService
    )
    {
        $info = $goodsService->getById($id);

        $form->setFormField("商品名称", 'text', 'name', 1, $info['name']);
        $form->setFormField("副标题", 'text', 'subhead',0, $info['subhead']);

        $form->setFormField("产品", 'search_select', 'productId', 0, $info['productId'], function ()use($info, $productService){
            $productInfo = $productService->getById($info['productId']);
            $tmp =[];
            if($productInfo){
                $tmp[$productInfo['name']] = $productInfo["id"];
            }
            dump($tmp);
            return [$this->generateUrl("admin_api_glob_searchProductDo"),$tmp];
        });

        $groupGoods = $goodsService->getGroupGoods($id);
        $form->setFormField("商品", 'search_multiple_select', 'goodsId[]', 0, $groupGoods, function ()use($id,$goodsService){
            $tmp = $goodsService->getSelectGoods($id);
            return [$this->generateUrl("admin_api_glob_searchGoodsDo"),$tmp];
        });

        $form->setFormField("类目", 'select', 'categoryId', 1, $info["categoryId"], function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });

        $form->setFormField("授课方式", 'select', 'teachingMethod' ,1, $info["teachingMethod"], function(){
            return ["面授"=>1, "直播"=>2, "录播"=>3, "直播+面授"=>4, "直播+录播"=>5, "录播+面授"=>6, "直播+录播+面授"=>7];
        });

        $teachers = $info["teachingTeacher"];
        $teachersId = $teachers?\GuzzleHttp\json_decode($teachers, true):[];
        dump($teachersId);
        $form->setFormField("上课老师", 'multiSelect', 'teachers[]', 1, $teachersId, function () use ($chapterService) {
            return $chapterService->getTeachers();
        });

        $form->setFormField("课时", 'text', 'courseHour', 1, $info["courseHour"]);
        $form->setFormField("课次", 'text', 'courseCount', 1, $info["courseCount"]);
        $form->setFormField("成本价", 'text', 'marketPrice', 1, $info["marketPrice"]/100);
        $form->setFormField("售价", 'text', 'shopPrice', 1, $info["shopPrice"]/100);
        $form->setFormField("购买人数", 'text', 'buyNumberFalse', 1, $info["buyNumberFalse"]);
        $form->setFormField("购买协议", 'select', 'agreementId' ,1, $info["agreementId"], function()use($productService){
            return $productService->getAgreements();
        });

        $options= [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_detail_goods"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024*50;//50m
        $options["data-required"] = 1;
        if($info) $options['data-initial-preview'] = $info['goodsImg'];
        if($info) $options['data-initial-preview-config']= $goodsService->getInitialPreviewConfig($info['goodsImg']);

        $form->setFormAdvanceField("详情页海报图", "file", 'goodsImg' , $options, $info['goodsImg']);

        $options= [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_small_goods"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024*50;//50m
        $options["data-required"] = 1;
        if($info) $options['data-initial-preview'] = $info['goodsSmallImg'];
        if($info) $options['data-initial-preview-config']= $goodsService->getInitialPreviewConfig($info['goodsSmallImg']);
        $form->setFormAdvanceField("缩略图", "file", 'goodsSmallImg' , $options,$info['goodsSmallImg']);
        $form->setFormField("是否上架", 'boole', 'status', 1, $info['status']);
        $form->setFormField("排序", 'text', 'sort', 1, $info['sort']);

        $formData = $form->create($this->generateUrl("admin_api_mall_goods_edit", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/mall/goods/edit.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/api/mall/goods/editDo/{id}", name="admin_api_mall_goods_edit")
     */
    public function editDoAction($id, Request $request, GoodsService $goodsService)
    {
        $name = $request->get("name");
        $productId = $request->get("productId");
        $goodsId = $request->get("goodsId");
        $categoryId = $request->get("categoryId");
        $subhead = $request->get("subhead");
        $teachingMethod = (int) $request->get("teachingMethod");
        $teachers = $request->get("teachers");
        $courseHour = $request->get("courseHour");
        $courseCount = $request->get("courseCount");
        $marketPrice = $request->get("marketPrice");
        $shopPrice = $request->get("shopPrice");
        $buyNumberFalse = (int)  $request->get("buyNumberFalse");
        $goodsImg = $request->get("goodsImg");
        $goodsSmallImg = $request->get("goodsSmallImg");
        $status = $request->get("status");
        $sort = (int) $request->get("sort");
        $agreementId = (int) $request->get("agreementId");
        $groupType =  (int) $request->get("groupType");

        $status = $status == "on" ? 1 : 0;

        if (! $name) return $this->responseError("商品名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("商品名称不能大于50字!");

        if($goodsService->checkName($name, $id)) return $this->responseError("商品名称已存在!");

        if(!$productId && !$goodsId) return $this->responseError("产品或者商品必须选其一!");

        if($goodsId){
            if($productId) return $this->responseError("组合商品不能添加产品!");
        }else{
            if(!$shopPrice) return $this->responseError("非组合商品商品售价不能为空!");
        }

        if($subhead){
            if (mb_strlen($subhead, 'utf-8') > 80) return $this->responseError("副标题不能大于80字!");
        }

        if(!$teachers){
            return $this->responseError("老师不能为空!");
        }

        $goodsService->edit($id, $name, $productId, $goodsId, $categoryId, $subhead,
            $teachingMethod, $teachers, $courseHour, $courseCount,
            $marketPrice,$shopPrice,$buyNumberFalse, $goodsImg, $goodsSmallImg, $status, $sort,$agreementId, $groupType);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_mall_goods_index'));
    }

    /**
     *
     * @Rest\Post("/api/mall/goods/deleteDo/{id}", name="admin_api_mall_goods_delete")
     */
    public function deleteDoAction($id, GoodsService $goodsService)
    {
        if ($goodsService->hasSub($id))
            return $this->responseError("删除失败，请先删除课程数据!");
        $goodsService->del($id);
        $info = $goodsService->getById($id);

        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_mall_goods_index"));
    }

    /**
     * @Rest\Post("/api/mall/goods/switchStatusDo/{id}", name="admin_api_mall_goods_switchStatus")
     */
    public function switchStatusAction($id, GoodsService $goodsService, Request $request){
        $state = (int) $request->get("state");
        $goodsService->switchStatus($id, $state);
        return $this->responseSuccess("操作成功!");
    }

}
