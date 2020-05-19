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

class PayController extends BaseAdminController
{

    /**
     *
     * @Rest\Get("/mall/pay/index", name="admin_mall_pay_index")
     */
    public function indexAction(Request $request, Grid $grid, OrderService $orderService, UserService $userService){
        $pageSize = 20;
        $grid->setListService($orderService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("订单号", "text", "orderNo");
        $grid->setTableColumn("支付流水号", "text", "transactionId");
        $grid->setTableColumn("支付状态", "text", "payStatus", "a.payStatus", [0=>"支付过期",1=>"待支付", 2=>"已支付", 3=>"已取消"]);
        $grid->setTableColumn("支付方式", "text", "paymentType", "a.paymentType", [1=>"支付宝", 2=>"微信"]);
        $grid->setTableColumn("支付人", "text", "creater", "a.uid");
        $grid->setTableColumn("支付金额", "text", "amount");
        $grid->setTableColumn("支付完成时间", "text", "payTime");
        $grid->setTableColumn("支付生成时间", "datetime", "createdAt", "a.createdAt");
        //搜索
        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("订单号", "text", "a.orderNo");
        $grid->setSearchField("支付流水号", "text", "a.transactionId");
        $grid->setSearchField("支付状态", "select", "a.payStatus", function(){
            return ["全部"=>-1,"支付失败"=>0, "待支付"=>1, "已支付"=>2];
        });
        $grid->setSearchField("支付方式", "select", "a.paymentType", function(){
            return ["全部"=>-1, "支付宝"=>1, "微信"=>2];
        });
        $grid->setSearchField("支付人", "search_select", "a.uid", function()use($request, $userService){
            $values = $request->get("values");
            $createUid = ($values&&isset($values["a.uid"]))?$values["a.uid"]:0;
            if($createUid){
                $users = $userService->searchResult($createUid);
            }else{
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchUserDo"),$users];
        });
        $grid->setSearchField("支付完成时间", "daterange2", "a.payTime");
        $grid->setSearchField("支付生成时间", "daterange", "a.createdAt");

        $data = [];

        $data['list'] = $grid->create($request, $pageSize);

        return $this->render("@AdminBundle/mall/order/index.html.twig", $data);

    }
}
