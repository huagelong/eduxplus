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
use App\Bundle\AdminBundle\Service\Mall\OrderService;

class OrderController extends BaseAdminController
{

    /**
     *
     * @Rest\Get("/mall/order/index", name="admin_mall_order_index")
     */
    public function indexAction(Request $request, Grid $grid, OrderService $orderService, CategoryService $categoryService, UserService $userService){
        $pageSize = 20;
        $grid->setListService($orderService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("订单名称", "text", "name");
        $grid->setTableColumn("订单号", "text", "orderNo");
        $grid->setTableColumn("订单状态", "text", "orderStatus", "a.orderStatus", [0=>"待支付", 1=>"已支付", 2=>"已取消"]);
        $grid->setTableColumn("下单来源", "text", "referer");
        $grid->setTableColumn("订单实际支付金额", "text", "orderAmount");
        $grid->setTableColumn("优惠金额", "text", "discountAmount");
        $grid->setTableColumn("优惠券", "text", "couponSn");
        $grid->setTableColumn("商品id", "text", "groupId");
        $grid->setTableColumn("商品组商品", "text", "Allgroup");
        $grid->setTableColumn("用户备注", "tip", "userNotes");
        $grid->setTableColumn("下单人", "text", "creater", "a.createUid");
        $grid->setTableColumn("下单时间", "datetime", "createdAt", "a.createdAt");
        //搜索
        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("订单名称", "text", "a.name");
        $grid->setSearchField("订单号", "text", "a.orderNo");
        $grid->setSearchField("订单状态", "select", "a.orderStatus", function(){
            return ["全部"=>-1,"待支付"=>0, "已支付"=>1, "已取消"=>2];
        });
        $grid->setSearchField("下单时间", "daterange", "a.createdAt");
        

        $data = [];

        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@AdminBundle/mall/order/index.html.twig", $data);

    }
}
