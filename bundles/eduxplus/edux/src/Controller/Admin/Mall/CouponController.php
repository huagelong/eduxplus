<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:01
 */

namespace Eduxplus\EduxBundle\Controller\Admin\Mall;


use Eduxplus\EduxBundle\Service\Mall\CouponService;
use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\EduxBundle\Service\Mall\GoodsService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CouponController extends BaseAdminController
{

    public function indexAction(Request $request,Grid $grid, CouponService $couponService, CategoryService $categoryService, UserService $userService){
        $pageSize = 40;
        $grid->setListService($couponService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("优惠券名称")->field("name");
        $grid->text("优惠类型")->field("couponType")->sort("a.couponType")->options([0=>"未知", 1=>"金额优惠", 2=>"折扣优惠"]);
        $grid->text("生成优惠码?")->field("hasCode")->sort("a.hasCode")->options([0=>"否", 1=>"是"]);
        $grid->text("折扣值")->field("discount");
        $grid->text("发放数量")->field("countNum");
        $grid->text("已使用数量")->field("usedNum");
        $grid->text("开始有效时间")->field("expirationStart");
        $grid->text("结束有效时间")->field("expirationEnd");
        $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("admin_api_mall_coupon_switchStatus",function($obj) use ($userService){
            $id = $userService->getPro($obj, "id");
            $defaultValue = $userService->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_mall_coupon_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue?"checked":"";
            $confirmStr = $defaultValue? "确认要下架吗？":"确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->text("创建人")->field("creater")->sort("a.createUid");
        $grid->text("分类")->field("category");
        $grid->text("授课方式")->field("teachingMethod")->sort("a.teachingMethod")->options([0=>"全部", 1=>"面授",2=>"直播", 3=>"点播", 4=>"直播+面授", 5=>"直播+点播", 6=>"点播+面授", 7=>"直播+点播+面授"]);

        $grid->textarea("商品id")->field("goodsIds");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->setTableAction('admin_mall_couponsub_index', function($obj) use ($couponService){
            $id = $couponService->getPro($obj, "id");
            $hasCode =$couponService->getPro($obj, "hasCode");
            if($hasCode){
                $url = $this->generateUrl('admin_mall_couponsub_index',['id'=>$id]);
                $str = '<a href='.$url.' data-width="1000px" data-title="优惠码管理" title="优惠码管理" class=" btn btn-info btn-xs"><i class="mdi mdi-database"></i></a>';
                return  $str;
            }else{
                return "";
            }
        });
        $grid->editAction("admin_mall_coupon_edit")->deleteAction("admin_api_mall_coupon_delete");
        //批量删除
        $grid->setBathDelete("admin_api_mall_coupon_bathdelete");
        $grid->gbAddButton("admin_mall_coupon_add");
        //搜索
        $select = $categoryService->categorySelect();
        $grid->snumber("ID")->field("a.id");
        $grid->stext("优惠券名称")->field("a.name");
        $grid->sselect("优惠券类型")->field("a.couponType")->options(["全部"=>-1,"金额优惠"=>1, "折扣优惠"=>2]);
        $grid->sselect("上架？")->field("a.status")->options(["全部"=>-1,"下架"=>0, "上架"=>1]);
        $grid->sselect("类别")->field("a.categoryId")->options($select);
        $grid->sselect("授课方式")->field("a.teachingMethod")->options(["全部"=>-1,"面授"=>1, "直播"=>2, "点播"=>3, "直播+面授"=>4, "直播+点播"=>5, "点播+面授"=>6, "直播+点播+面授"=>7]);
        $grid->ssearchselect("创建人")->field("a.createUid")->options(function()use($request, $userService){
            $values = $request->get("values");
            $createUid = ($values&&isset($values["a.createUid"]))?$values["a.createUid"]:0;
            if($createUid){
                $users = $userService->searchResult($createUid);
            }else{
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchAdminUserDo"),$users];
        });
        $grid->sdaterange("创建时间")->field("a.createdAt");

        return $this->content()->renderList($grid->create($request, $pageSize));

    }

    public function addAction(Form $form, CouponService $couponService, CategoryService $categoryService){
        $form->text("优惠券名称")->field("name")->isRequire();
        $form->select("优惠券类型")->field("couponType")->isRequire()->options(["请选择"=>0,"金额优惠"=>1, "折扣优惠"=>2]);
        $form->boole("生成优惠码?")->field("hasCode")->isRequire();
        $form->text("折扣值")->field("discount")->isRequire();
        $form->text("发放数量")->field("countNum")->isRequire();
        $form->datetime("开始有效时间")->field("expirationStart")->isRequire();
        $form->datetime("结束有效时间")->field("expirationEnd")->isRequire();
        $form->boole("上架？")->field("status")->isRequire();
        $form->select("类目")->field("categoryId")->isRequire(0)->options(function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });

        $form->select("授课方式")->field("teachingMethod")->isRequire(1)->options(["全部"=>0,"面授"=>1, "直播"=>2, "点播"=>3, "直播+面授"=>4, "直播+点播"=>5, "点播+面授"=>6, "直播+点播+面授"=>7]);
        $form->searchMultipleSelect("优惠对应商品")->field("goodsIds[]")->isRequire(0)->options([$this->generateUrl("admin_api_glob_searchGoodsDo"),[]]);
        $form->textarea("描述")->field("descr")->isRequire(0);

        $formData = $form->create($this->generateUrl("admin_api_mall_coupon_add"));
        return $this->content()->title("添加优惠券")
                ->breadcrumb("优惠券管理", "admin_mall_coupon_index")
                ->renderAdd($formData);
    }


    public function addDoAction(Request $request, CouponService $couponService)
    {
        $name = $request->get("name");
        $couponType = (int) $request->get("couponType");
        $discount =  $request->get("discount");
        $countNum = (int) $request->get("countNum");
        $expirationStart = $request->get("expirationStart");
        $expirationEnd = $request->get("expirationEnd");
        $status = $request->get("status");
        $categoryId = (int) $request->get("categoryId");
        $teachingMethod = (int) $request->get("teachingMethod");
        $goodsIds = $request->get("goodsIds");
        $descr = $request->get("descr");
        $hasCode = $request->get("hasCode");
        $hasCode = $hasCode == "on" ? 1 : 0;

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
                 $expirationEndTime, $status, $categoryId, $teachingMethod, $goodsIds,$hasCode, $descr);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_coupon_index'));
    }

    public function editAction($id, Form $form, CouponService $couponService, CategoryService $categoryService, GoodsService $goodsService){

        $info = $couponService->getById($id);

        $form->text("优惠券名称")->field("name")->isRequire()->defaultValue($info['name']);
        $form->select("优惠券类型")->field("couponType")->isRequire()->options(["请选择"=>0,"金额优惠"=>1, "折扣优惠"=>2])->defaultValue($info['couponType']);
        $form->boole("生成优惠码?")->field("hasCode")->isRequire()->defaultValue($info['hasCode']);
        $form->text("折扣值")->field("discount")->isRequire()->defaultValue($info['discount']/100);
        $form->text("发放数量")->field("countNum")->isRequire()->defaultValue($info['countNum']);

        $expirationStart = date('Y-m-d H:i:s', $info["expirationStart"]);
        $expirationEnd = date('Y-m-d H:i:s', $info["expirationEnd"]);

        $form->datetime("开始有效时间")->field("expirationStart")->isRequire()->defaultValue($expirationStart);
        $form->datetime("结束有效时间")->field("expirationEnd")->isRequire()->defaultValue($expirationEnd);
        $form->boole("上架？")->field("status")->isRequire()->defaultValue($info['status']);
        $form->select("类目")->field("categoryId")->isRequire(0)->defaultValue($info["categoryId"])->options(function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });

        $form->select("授课方式")->field("teachingMethod")->isRequire(1)->defaultValue($info["teachingMethod"])->options(["全部"=>0,"面授"=>1, "直播"=>2, "点播"=>3, "直播+面授"=>4, "直播+点播"=>5, "点播+面授"=>6, "直播+点播+面授"=>7]);
        $goodsIdArr = [];
        if($info["goodsIds"]){
            $goodsIdArr = explode(",", $info["goodsIds"]);
        }

        $form->searchMultipleSelect("优惠对应商品")->field("goodsIds[]")->isRequire(0)->defaultValue($goodsIdArr)->options(function ()use($goodsIdArr, $goodsService){
            $cate = !$goodsIdArr?[]:$goodsService->getSelectByIds($goodsIdArr);
            return [$this->generateUrl("admin_api_glob_searchGoodsDo"),$cate];
        });
        $form->textarea("描述")->field("descr")->isRequire(0)->defaultValue($info["descr"]);

        $formData = $form->create($this->generateUrl("admin_api_mall_coupon_edit",["id"=>$id]));
        return $this->content()->renderEdit($formData);
    }


    public function editDoAction($id, Request $request, CouponService $couponService)
    {
        $name = $request->get("name");
        $couponType = (int) $request->get("couponType");
        $discount = $request->get("discount");
        $countNum = (int) $request->get("countNum");
        $expirationStart = $request->get("expirationStart");
        $expirationEnd = $request->get("expirationEnd");
        $status = $request->get("status");
        $categoryId = (int) $request->get("categoryId");
        $teachingMethod = (int) $request->get("teachingMethod");
        $goodsIds = $request->get("goodsIds");
        $descr = $request->get("descr");
        $hasCode = $request->get("hasCode");
        $hasCode = $hasCode == "on" ? 1 : 0;

        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("优惠券名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("优惠券名称不能大于50字!");
        if(!$expirationStart) return $this->responseError("过期开始时间不能为空!");
        if(!$expirationEnd) return $this->responseError("过期结束时间不能为空!");

        if(!$hasCode){
            //判断优惠券是否已经生成
            if($couponService->getCouponCodeCountByGroupId($id) >0) return $this->responseError("优惠券码已生成，不能修改为不生成优惠券码!");
        }

        $expirationStartTime = strtotime($expirationStart);
        $expirationEndTime = strtotime($expirationEnd);
        if($expirationEndTime < $expirationStartTime) return $this->responseError("过期开始时间不能大于结束时间!");
        $couponService->edit($id, $name, $couponType, $discount,$countNum, $expirationStartTime,
                 $expirationEndTime, $status, $categoryId, $teachingMethod, $goodsIds, $hasCode, $descr);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_mall_coupon_index'));
    }

    public function deleteDoAction($id, CouponService $couponService)
    {
        $couponService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl('admin_mall_coupon_index'));
    }

    public function bathdeleteDoAction(Request $request, CouponService $couponService)
    {

        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $couponService->del($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }


        return $this->responseMsgRedirect("删除成功!", $this->generateUrl('admin_mall_coupon_index'));
    }

    public function switchStatusAction($id, CouponService $couponService, Request $request){
        $state = (int) $request->get("state");
        $couponService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

    public function subAction($id, Request $request,Grid $grid, CouponService $couponService){
        $couponGroupInfo = $couponService->getById($id);
        $pageSize = 40;
        $grid->setListService($couponService, "getSubList", $id);
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->code("优惠券编码")->field("couponSn");
        $grid->text("使用时间")->field("usedTime");
        $grid->text("赠送时间")->field("sendTime");
        $grid->text("使用状态")->field("status")->sort("a.status")->options([0=>"未使用", 1=>"已使用"]);
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        if($couponGroupInfo['countNum']>$couponGroupInfo['createdNum']){
            if($couponGroupInfo['countNum']!=$couponGroupInfo['createdNum']){
                $str = "(已生成:{$couponGroupInfo['createdNum']})";
            }else{
                $str = "";
            }
            $grid->gbButton("生成")->route("admin_mall_couponsub_create")
                ->styleClass("btn-primary")->iconClass("mdi mdi-gavel ");
        }

        $grid->gbButton("导出")->route("admin_mall_couponsub_export",["id"=>$id])
            ->styleClass("btn-success")->iconClass("mdi mdi-download")->isBlank(1);

        //搜索
        $grid->snumber("ID")->field("a.id");
        $grid->stext("优惠券编码")->field("a.couponSn");
        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    public function subCouponCreateAction($id, CouponService $couponService){
        set_time_limit(0);
        // ignore_user_abort(true);
        $couponService->createCoupon($id);
        return $this->redirectToRoute("admin_mall_couponsub_index", ['id'=>$id]);
    }

    public function subexportAction($id, CouponService $couponService){
        $path =  $couponService->export($id);
        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }

}
