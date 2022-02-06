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
use Eduxplus\EduxBundle\Service\Mall\PayService;

class PayController extends BaseAdminController
{

    
    public function indexAction(Request $request, Grid $grid, PayService $payService, UserService $userService){
        $pageSize = 40;
        $grid->setListService($payService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("订单号")->field("orderNo");
        $grid->text("支付流水号")->field("transactionId");
        $grid->text("支付状态")->field("orderStatus")->options([0=>"支付过期",1=>"待支付", 2=>"已支付", 3=>"已取消"]);
        $grid->text("支付方式")->field("paymentType")->options( [0=>"免费",1=>"支付宝", 2=>"微信"]);
        $grid->text("支付人")->field("creater")->sort("a.uid");
        $grid->text("支付金额")->field("amount");
        $grid->text("支付完成时间")->field("payTime");
        $grid->datetime("支付生成时间")->field("createdAt")->sort("a.createdAt");
        $grid->snumber("ID")->field("a.id");
        $grid->stext("订单号")->field("a.orderNo");
        $grid->stext("支付流水号")->field("a.transactionId");
        $grid->sselect("支付状态")->field("a.payStatus")->options(["全部"=>-1,"支付失败"=>0, "待支付"=>1, "已支付"=>2]);
        $grid->sselect("支付方式")->field("a.paymentType")->options(["全部"=>-1,0=>"免费", "支付宝"=>1, "微信"=>2]);
        $grid->ssearchselect("支付人")->field("a.uid")->options(function()use($request, $userService){
            $values = $request->get("values");
            $createUid = ($values&&isset($values["a.uid"]))?$values["a.uid"]:0;
            if($createUid){
                $users = $userService->searchResult($createUid);
            }else{
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchUserDo"),$users];
        });
        $grid->sdaterange("支付生成时间")->field("a.createdAt");
        $grid->sdaterange2("支付完成时间")->field("a.payTime");
        return $this->content()->renderList($grid->create($request, $pageSize));

    }
}
