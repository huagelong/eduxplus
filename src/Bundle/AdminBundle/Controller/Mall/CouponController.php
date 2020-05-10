<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:01
 */

namespace App\Bundle\AdminBundle\Controller\Mall;


use App\Bundle\AdminBundle\Service\Mall\CouponService;
use App\Bundle\AdminBundle\Service\Teach\CategoryService;
use App\Bundle\AdminBundle\Service\UserService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;
use App\Bundle\AdminBundle\Service\Mall\GoodsService;

class CouponController extends BaseAdminController
{

    /**
     *
     * @Rest\Get("/mall/coupon/index", name="admin_mall_coupon_index")
     */
    public function indexAction(Request $request,Grid $grid, CouponService $couponService, CategoryService $categoryService, UserService $userService){
        $pageSize = 20;
        $grid->setListService($couponService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("优惠券名称", "text", "couponName");
        $grid->setTableColumn("优惠类型", "text", "couponType", "a.couponType", [0=>"未知", 1=>"金额优惠", 2=>"折扣优惠"]);
        $grid->setTableColumn("折扣值", "text", "discount");
        $grid->setTableColumn("发放数量", "text", "countNum");
        $grid->setTableColumn("已使用数量", "text", "usedNum");
        $grid->setTableColumn("开始有效时间", "text", "expirationStart");
        $grid->setTableColumn("结束有效时间", "text", "expirationEnd");

        $grid->setTableActionColumn("admin_api_mall_coupon_switchStatus", "上架？", "boole2", "status", "a.status",null,function($obj){
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_mall_coupon_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue?"checked":"";
            $confirmStr = $defaultValue? "确认要下架吗？":"确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });

        $grid->setTableColumn("创建人", "text", "creater", "a.createUid");
        $grid->setTableColumn("分类", "text", "category");
        $grid->setTableColumn("授课方式", "text", "teachingMethod", "a.teachingMethod", [0=>"全部", 1=>"面授",2=>"直播", 3=>"录播", 4=>"直播+面授", 5=>"直播+录播", 6=>"录播+面授", 7=>"直播+录播+面授"]);
        $grid->setTableColumn("商品id", "textarea", "goodsIds");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");


        $grid->setTableAction('admin_mall_couponsub_index', function($obj){
            $id = $obj['id'];
            $url = $this->generateUrl('admin_mall_couponsub_index',['id'=>$id]);
            $str = '<a href='.$url.' data-width="1000px" data-title="优惠码管理" title="优惠码管理" class=" btn btn-info btn-xs"><i class="fas fa-database"></i></a>';
            return  $str;
        });
        
        $grid->setTableAction('admin_mall_coupon_edit', function($obj){
            $id = $obj['id'];
            $url = $this->generateUrl('admin_mall_coupon_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_mall_coupon_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_api_mall_coupon_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $grid->setGridBar("admin_mall_coupon_add","添加", $this->generateUrl("admin_mall_coupon_add"), "fas fa-plus", "btn-success");

        //搜索
        $select = $categoryService->categorySelect();
        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("优惠券名称", "text", "a.couponName");
        $grid->setSearchField("优惠券类型", "select", "a.couponType", function(){
            return ["全部"=>-1,"金额优惠"=>1, "折扣优惠"=>2];
        });

        $grid->setSearchField("上架？", "select", "a.status", function(){
            return ["全部"=>-1,"下架"=>0, "上架"=>1];
        });

        $grid->setSearchField("类别", 'select', 'a.categoryId' , function()use($select){
            return $select;
        });


        $grid->setSearchField("授课方式", "select", "a.teachingMethod", function(){
            return ["全部"=>-1,"面授"=>1, "直播"=>2, "录播"=>3, "直播+面授"=>4, "直播+录播"=>5, "录播+面授"=>6, "直播+录播+面授"=>7];
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

        $grid->setSearchField("创建时间", "daterange", "a.createdAt");


        $data = [];

        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@AdminBundle/mall/coupon/index.html.twig", $data);

    }

    /**
     *
     * @Rest\Get("/mall/coupon/add", name="admin_mall_coupon_add")
     */

    public function addAction(Form $form, CouponService $couponService, CategoryService $categoryService){
        $form->setFormField("优惠券名称", 'text', 'couponName', 1);
        $form->setFormField("优惠券类型", 'select', 'couponType' ,1, "", function(){
            return ["请选择"=>0,"金额优惠"=>1, "折扣优惠"=>2];
        });
        $form->setFormField("折扣值", 'text', 'discount', 1);
        $form->setFormField("发放数量", "text", "countNum", 1);
        $form->setFormField("开始有效时间", 'datetime', 'expirationStart',1);
        $form->setFormField("结束有效时间", 'datetime', 'expirationEnd',1);
        $form->setFormField("上架？", 'boole', 'status', 1);
        $form->setFormField("类目", 'select', 'categoryId', 0, "", function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });
        $form->setFormField("授课方式", 'select', 'teachingMethod' ,1, "", function(){
            return ["面授"=>1, "直播"=>2, "录播"=>3, "直播+面授"=>4, "直播+录播"=>5, "录播+面授"=>6, "直播+录播+面授"=>7];
        });

        $form->setFormField("优惠对应商品", 'search_multiple_select', 'goodsIds[]', 0, "", function (){
            return [$this->generateUrl("admin_api_glob_searchGoodsDo"),[]];
        });

        $form->setFormField("描述", 'textarea', 'descr', 0);

        $formData = $form->create($this->generateUrl("admin_api_mall_coupon_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/mall/coupon/add.html.twig", $data);
    }


    /**
     *
     * @Rest\Post("/api/mall/coupon/addDo", name="admin_api_mall_coupon_add")
     */
    public function addDoAction(Request $request, CouponService $couponService)
    {
        $name = $request->get("couponName");
        $couponType = (int) $request->get("couponType");
        $discount = (int) $request->get("discount");
        $countNum = (int) $request->get("countNum");
        $expirationStart = $request->get("expirationStart");
        $expirationEnd = $request->get("expirationEnd");
        $status = $request->get("status");
        $categoryId = (int) $request->get("categoryId");
        $teachingMethod = (int) $request->get("teachingMethod");
        $goodsIds = $request->get("goodsIds");
        $descr = $request->get("descr");
        $status = $status == "on" ? 1 : 0;
        $uid = $this->getUid();
        if (!$name) return $this->responseError("优惠券名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("优惠券名称不能大于50字!");
        if($couponService->getByName($name)) return $this->responseError("优惠券名称重复!");
        if(!$expirationStart) return $this->responseError("过期开始时间不能为空!");
        if(!$expirationEnd) return $this->responseError("过期结束时间不能为空!");
        
        $expirationStartTime = strtotime($expirationStart);
        $expirationEndTime = strtotime($expirationEnd);
        if($expirationEndTime < $expirationStartTime) return $this->responseError("过期开始时间不能大于结束时间!");
        
        $couponService->add($uid, $name, $couponType, $discount,$countNum, $expirationStartTime,
                 $expirationEndTime, $status, $categoryId, $teachingMethod, $goodsIds, $descr);
        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_mall_coupon_index'));
    }

    /**
     *
     * @Rest\Get("/mall/coupon/edit/{id}", name="admin_mall_coupon_edit")
     */
    public function editAction($id, Form $form, CouponService $couponService, CategoryService $categoryService, GoodsService $goodsService){

        $info = $couponService->getById($id);
        
        $form->setFormField("优惠券名称", 'text', 'couponName', 1, $info['couponName']);
        $form->setFormField("优惠券类型", 'select', 'couponType' ,1, $info['couponType'], function(){
            return ["请选择"=>0,"金额优惠"=>1, "折扣优惠"=>2];
        });
        $form->setFormField("折扣值", 'text', 'discount', 1, $info['discount']/100);
        $form->setFormField("发放数量", "text", "countNum", 1, $info['countNum']);
        $expirationStart = date('Y-m-d H:i:s', $info["expirationStart"]);
        $expirationEnd = date('Y-m-d H:i:s', $info["expirationEnd"]);
        $form->setFormField("开始有效时间", 'datetime', 'expirationStart',1, $expirationStart);
        $form->setFormField("结束有效时间", 'datetime', 'expirationEnd',1, $expirationEnd);
        $form->setFormField("上架？", 'boole', 'status', 1, $info['status']);
        $form->setFormField("类目", 'select', 'categoryId', 0, $info["categoryId"], function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });
        $form->setFormField("授课方式", 'select', 'teachingMethod' ,1, $info["teachingMethod"], function(){
            return ["面授"=>1, "直播"=>2, "录播"=>3, "直播+面授"=>4, "直播+录播"=>5, "录播+面授"=>6, "直播+录播+面授"=>7];
        });
        $goodsIdArr = [];
        if($info["goodsIds"]){
            $goodsIdArr = explode(",", $info["goodsIds"]);
        }
        $form->setFormField("优惠对应商品", 'search_multiple_select', 'goodsIds[]', 0, $goodsIdArr, function ()use($goodsIdArr, $goodsService){
            $cate = $goodsIdArr?[]:$goodsService->getSelectByIds($goodsIdArr);
            return [$this->generateUrl("admin_api_glob_searchGoodsDo"),$cate];
        });

        $form->setFormField("描述", 'textarea', 'descr', 0, $info["descr"]);

        $formData = $form->create($this->generateUrl("admin_api_mall_coupon_edit"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/mall/coupon/edit.html.twig", $data);
    }


    /**
     *
     * @Rest\Post("/api/mall/coupon/editDo/{id}", name="admin_api_mall_coupon_edit")
     */
    public function editDoAction($id, Request $request, CouponService $couponService)
    {
        $name = $request->get("couponName");
        $couponType = (int) $request->get("couponType");
        $discount = (int) $request->get("discount");
        $countNum = (int) $request->get("countNum");
        $expirationStart = $request->get("expirationStart");
        $expirationEnd = $request->get("expirationEnd");
        $status = $request->get("status");
        $categoryId = (int) $request->get("categoryId");
        $teachingMethod = (int) $request->get("teachingMethod");
        $goodsIds = $request->get("goodsIds");
        $descr = $request->get("descr");
        $status = $status == "on" ? 1 : 0;
    
        if (!$name) return $this->responseError("优惠券名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("优惠券名称不能大于50字!");
        if(!$expirationStart) return $this->responseError("过期开始时间不能为空!");
        if(!$expirationEnd) return $this->responseError("过期结束时间不能为空!");
        
        $expirationStartTime = strtotime($expirationStart);
        $expirationEndTime = strtotime($expirationEnd);
        if($expirationEndTime < $expirationStartTime) return $this->responseError("过期开始时间不能大于结束时间!");
        
        $couponService->edit($id, $name, $couponType, $discount,$countNum, $expirationStartTime,
                 $expirationEndTime, $status, $categoryId, $teachingMethod, $goodsIds, $descr);
        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_mall_coupon_index'));
    }

    /**
     *
     * @Rest\Post("/api/mall/coupon/deleteDo/{id}", name="admin_api_mall_coupon_delete")
     */
    public function deleteDoAction($id, CouponService $couponService)
    {
        $couponService->del($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl('admin_mall_coupon_index'));
    }

    /**
     * @Rest\Post("/api/mall/coupon/switchStatusDo/{id}", name="admin_api_mall_coupon_switchStatus")
     */
    public function switchStatusAction($id, CouponService $couponService, Request $request){
        $state = (int) $request->get("state");
        $couponService->switchStatus($id, $state);
        return $this->responseSuccess("操作成功!");
    }

    /**
     *
     * @Rest\Get("/mall/couponsub/index/{id}", name="admin_mall_couponsub_index")
     */
    public function subAction($id, Request $request,Grid $grid, CouponService $couponService){
        $pageSize = 20;
        $grid->setListService($couponService, "getSubList", $id);
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("优惠券编码", "text", "couponSn");
        $grid->setTableColumn("使用时间", "text", "usedTime");
        $grid->setTableColumn("赠送时间", "text", "sendTime");
        $grid->setTableColumn("使用状态", "text", "status", "a.status", [0=>"未使用", 1=>"已使用"]);
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");
        $grid->setGridBar("admin_mall_couponsub_create","生成", $this->generateUrl("admin_mall_couponsub_create", ["id"=>$id]), "fas fa-gavel", "btn-primary", 1);
        $grid->setGridBar("admin_mall_couponsub_export","导出", $this->generateUrl("admin_mall_couponsub_export", ["id"=>$id]), "fas fa-download", "btn-success", 1);
        //搜索
        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("优惠券编码", "text", "a.couponSn");
        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/mall/coupon/subindex.html.twig", $data);
    }

    /**
     * 生成优惠券码
     * @Rest\Get("/mall/couponsub/create/{id}", name="admin_mall_couponsub_create")
     */
    public function subCreateCouponAction($id, CouponService $couponService){
        $couponService->createCoupon($id);
        return $this->render("@AdminBundle/mall/coupon/subCreateCoupon.html.twig", $data);
    }

    /**
     *
     * @Rest\Get("/mall/couponsub/export/{id}", name="admin_mall_couponsub_export")
     */
    public function subexportAction($id){

    }

}
