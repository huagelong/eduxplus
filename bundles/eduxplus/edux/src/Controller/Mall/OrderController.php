<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:01
 */

namespace Eduxplus\EduxBundle\Controller\Mall;


use Eduxplus\EduxBundle\Service\Mall\CouponService;
use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\EduxBundle\Service\Mall\GoodsService;
use Eduxplus\EduxBundle\Service\Mall\OrderService;

class OrderController extends BaseAdminController
{

    /**
     *
     * @Route("/mall/order/index", name="admin_mall_order_index")
     */
    public function indexAction(Request $request, Grid $grid, OrderService $orderService, UserService $userService){
        $pageSize = 40;
        $grid->setListService($orderService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("订单名称")->field("name");
        $grid->text("订单号")->field("orderNo");
        $grid->text("订单状态")->field("orderStatus")->sort("a.orderStatus")->options([0=>"支付过期",1=>"待支付", 2=>"已支付", 3=>"已取消"]);
        $grid->text("下单来源")->field("referer");
        $grid->text("原价")->field("originalAmount");
        $grid->text("订单实际支付金额")->field("orderAmount");
        $grid->text("优惠金额")->field("discountAmount");
        $grid->text("优惠券")->field("couponSn");
        $grid->tip("商品")->field("goodName");
        $grid->text("组合商品")->field("allGoodNames");
        $grid->tip("用户备注")->field("userNotes");
        $grid->text("下单人")->field("creater")->sort("a.uid");
        $grid->datetime("下单时间")->field("createdAt")->sort("a.createdAt");

        //搜索
        $grid->snumber("ID")->field("a.id");
        $grid->stext("订单名称")->field("a.name");
        $grid->stext("订单号")->field("a.orderNo");
        $grid->sselect("订单状态")->field("a.orderStatus")->options(["全部"=>-1,"待支付"=>0, "已支付"=>1, "已取消"=>2]);
        $grid->ssearchselect("下单人")->field("a.uid")->options(function()use($request, $userService){
            $values = $request->get("values");
            $createUid = ($values&&isset($values["a.uid"]))?$values["a.uid"]:0;
            if($createUid){
                $users = $userService->searchResult($createUid);
            }else{
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchUserDo"),$users];
        });
        $grid->sdaterange("下单时间")->field("a.createdAt");


        $data = [];

        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@EduxBundle/mall/order/index.html.twig", $data);

    }
}
