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
    public function indexAction(Request $request,Grid $grid, GoodsService $goodsService, CategoryService $categoryService)
    {
        $pageSize = 20;
        $grid->setListService($goodsService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("商品名称", "text", "goodsName");
        $grid->setTableColumn("品类", "text", "brand");
        $grid->setTableColumn("类目", "text", "category");
        $grid->setTableColumn("授课方式", "text", "teachingMethod", "a.teachingMethod", [1=>"面授",2=>"直播", 3=>"录播", 4=>"直播+面授", 5=>"直播+录播", 6=>"录播+面授", 7=>"直播+录播+面授"]);
        $grid->setTableColumn("课时", "text", "courseHour");
        $grid->setTableColumn("课次", "text", "courseount");
        $grid->setTableColumn("成本价", "text", "marketPrice");
        $grid->setTableColumn("售价", "text", "shopPrice");
        $grid->setTableColumn("购买人数", "text", "buyNumber");
        $grid->setTableColumn("详情页海报图", "image", "goodsImg");
        $grid->setTableColumn("缩略图", "image", "goodsSmallImg");
        $grid->setTableActionColumn("admin_api_mall_goods_switchStatus", "是否上架", "boole2", "status", null,null,function($obj){
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_mall_goods_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue?"checked":"";
            $confirmStr = $defaultValue? "确认要下架吗？":"确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->setTableColumn("创建人", "text", "creater");
        $grid->setTableColumn("是否是组合商品", "boole", "isGroup");
        $grid->setTableColumn("组合商品类型", "text", "groupType", "a.groupType", [0=>"-", 1=>"可选", 2=>"全选"]);
        $grid->setTableColumn("协议", "text", "agreement");
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
        $grid->setSearchField("类型", "select", "a.teachingMethod", function(){
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
//        print_r($all);exit;
        return $this->render("@AdminBundle/mall/goods/index.html.twig", $data);
    }

    /**
     *
     * @Rest\Get("/mall/goods/add", name="admin_mall_goods_add")
     */
    public function addAction(Form $form, Request $request, GoodsService $goodsService, CategoryService $categoryService)
    {
        $form->setFormField("商品名称", 'text', 'name', 1);
        $form->setFormField("副标题", 'text', 'subhead');
        $form->setFormField("是否当前默认计划", 'boole', 'isDefault', 1);
        $form->setFormField("是否有挡板", 'boole', 'isBlock', 1);
        $form->setFormField("预计报名时间", 'datetime', 'applyedAt');
        $form->setFormField("产品", 'search_select', 'productId[]', 1, "", function (){
            return [$this->generateUrl("admin_api_glob_searchProductDo"),[]];
        });

        $form->setFormField("描述", 'textarea', 'descr');

        $formData = $form->create($this->generateUrl("admin_api_mall_goods_add", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        return $this->render("@AdminBundle/mall/goods/add.html.twig", $data);
    }

    /**
     * @Rest\Get("/api/mall/goods/searchCourseDo", name="admin_api_mall_goods_searchCourseDo")
     */
    public function searchCourseDoAction(Request $request, GoodsService $goodsService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $goodsService->searchCourseName($kw);
        return $data;
    }

    /**
     *
     * @Rest\Post("/api/mall/goods/addDo", name="admin_api_mall_goods_add")
     */
    public function addDoAction(Request $request, GoodsService $goodsService)
    {
        $name = $request->get("name");
        $isDefault = $request->get("isDefault");
        $isBlock = $request->get("isBlock");
        $applyedAt = $request->get("applyedAt");
        $courseIds = $request->get("courseId");
        $descr = $request->get("descr");

        $isDefault = $isDefault == "on" ? 1 : 0;
        $isBlock = $isBlock == "on" ? 1 : 0;

        if (! $name) return $this->responseError("课程计划名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("课程计划名称不能大于50字!");

        if(!$courseIds) return $this->responseError("课程必须选择!");

        $uid = $this->getUid();
        $goodsService->add($id, $name, $isDefault, $isBlock, $applyedAt, $courseIds, $uid, $descr);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_mall_goods_index', [
            'id' => $id
        ]));
    }

    /**
     *
     * @Rest\Get("/mall/goods/edit/{id}", name="admin_mall_goods_edit")
     */
    public function editAction($id, Form $form, GoodsService $goodsService)
    {
        $info = $goodsService->getById($id);

        $form->setFormField("名称", 'text', 'name', 1, $info['name']);
        $form->setFormField("是否当前默认计划", 'boole', 'isDefault', 1, $info['isDefault']);
        $form->setFormField("是否有挡板", 'boole', 'isBlock', 1, $info['isBlock']);
        $form->setFormField("预计报名时间", 'datetime', 'applyedAt', 0, $info['applyedAt']?date('Y-m-d H:i:s',$info['applyedAt']):"");
        $form->setFormField("课程", 'search_select', 'courseId[]', 1, $info['sub'], function ()use($goodsService,$info){
            return [$this->generateUrl("admin_api_mall_goods_searchCourseDo"), $goodsService->getCourseByIds($info['sub'])];
        });

        $form->setFormField("描述", 'textarea', 'descr',0, $info['descr']);

        $formData = $form->create($this->generateUrl("admin_api_mall_goods_edit", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        return $this->render("@AdminBundle/mall/goods/edit.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/api/mall/goods/editDo/{id}", name="admin_api_mall_goods_edit")
     */
    public function editDoAction($id, Request $request, GoodsService $goodsService)
    {
        $name = $request->get("name");
        $isDefault = $request->get("isDefault");
        $isBlock = $request->get("isBlock");
        $applyedAt = $request->get("applyedAt");
        $courseIds = $request->get("courseId");
        $descr = $request->get("descr");

        $isDefault = $isDefault == "on" ? 1 : 0;
        $isBlock = $isBlock == "on" ? 1 : 0;

        if (! $name) return $this->responseError("课程计划名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("课程计划名称不能大于50字!");

        if(!$courseIds) return $this->responseError("课程必须选择!");

        $goodsService->edit($id, $name, $isDefault, $isBlock, $applyedAt, $courseIds, $descr);

        $info = $goodsService->getById($id);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_mall_goods_index', [
            'id' => $info['productId']
        ]));
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

        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_mall_goods_index",['id' => $info['productId']]));
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
