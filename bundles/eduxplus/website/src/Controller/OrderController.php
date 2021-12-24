<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/15 11:15
 */

namespace Eduxplus\WebsiteBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\CoreBundle\Lib\Service\Pay\AlipayService;
use Eduxplus\CoreBundle\Lib\Service\Pay\WxpayService;
use Eduxplus\WebsiteBundle\Service\GoodsService;
use Eduxplus\WebsiteBundle\Service\OrderService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class OrderController extends BaseHtmlController
{
    /**
     * 我的订单列表
     *
     * @Route("/my/orders", name="app_order_index")
     */
    public function indexAction(Request $request, OrderService $orderService)
    {
        $page = $request->get("page", 1);
        $pageSize = 40;

        $uid = $this->getUid();
        $data = [];
        list($pagination, $list) = $orderService->getList($uid, $page, $pageSize);
        $data['list'] = $list;
        $data['route'] = "app_order_index";
        $data['pagination'] = $pagination;
        return $this->render('@WebsiteBundle/order/index.html.twig', $data);
    }

    /**
     * 订单详情
     *
     * @Route("/my/order/detail/{orderNo}", name="app_order_detail")
     */
    public function detailAction($orderNo,OrderService $orderService)
    {
        $detail = $orderService->getByOrderNo($orderNo);
        $data = [];
        $data['order'] = $detail;
        $data['route'] = "app_order_index";
        return $this->render('@WebsiteBundle/order/detail.html.twig', $data);
    }

    /**
     * 去购买
     *
     * @Route("/my/tobuy/{uuid}", name="app_order_tobuy")
     */
    public function tobuyAction($uuid,Request $request, GoodsService $goodsService, OrderService $orderService){
        $detail = $goodsService->getSimpleByUuid($uuid);
        $goodsIdstr = $request->get("goodsIdstr");
        $goodsIds = $request->get("goodsId");
        $couponSn = $request->get("couponSn");
        if($goodsIdstr){
            $goodsIds = explode(",", $goodsIdstr);
        }

        $uid = $this->getUid();
        list($shopPrice, $discount, $goodsList, $couponWarn) = $orderService->getOrderRealPrice($detail, $goodsIds, $couponSn, $uid);

        $discount = $discount/100;
        $shopPrice = $shopPrice/100;
        $shopPrice = number_format(round($shopPrice,2), 2);
        $discount = number_format(round($discount,2), 2);

        if($goodsIds){
            $goodsIdstr = implode(",", $goodsIds);
        }
        $data= [];
        $data["detail"] = $detail;
        $data["goodsList"] = $goodsList;
        $data['shopPrice'] = $shopPrice;
        $data['discount'] = $discount;
        $data['couponWarn'] = $couponWarn;
        $data['couponSn'] = $couponSn;
        $data['goodsIdstr'] = $goodsIdstr;
        $data['uuid'] = $uuid;
        return $this->render('@WebsiteBundle/order/tobuy.html.twig', $data);
    }

    /**
     * 去购买
     *
     * @Route("/my/addbuy/{uuid}", name="app_order_addbuy")
     */
    public function dobuyAction($uuid,Request $request, GoodsService $goodsService, OrderService $orderService,CsrfTokenManagerInterface $csrfTokenManager){
        $userNotes = $request->get("userNotes");
        $couponSn = $request->get("couponSn");
        $goodsIdstr = $request->get("goodsIdstr");
        $paymentType = $request->get("paymentType");
        $csrfToken = $request->get('CsrfToken');
        $goodsIds = [];
        $goodsAll = [];

        $token = new CsrfToken('dobuy', $csrfToken);
        if (!$csrfTokenManager->isTokenValid($token)) {
            return $this->showMsg("重复提交");
        }
        //删除csrf
        $csrfTokenManager->removeToken("dobuy");

        if($userNotes){
            if(mb_strlen($userNotes)>90){
                return $this->showMsg("备注内容不能超过90字");
            }
        }

        if($goodsIdstr){
            $goodsIds = explode(",", $goodsIdstr);
        }

        $detail = $goodsService->getSimpleByUuid($uuid);
        $uid = $this->getUid();
        list($orderAmount, $discountAmount, $goodsList, $couponWarn, $groupCouponId) = $orderService->getOrderRealPrice($detail, $goodsIds, $couponSn, $uid);
        if($couponWarn){
            return $this->showMsg($couponWarn);
        }
        $uid = $this->getUid();
        $name = $detail["name"];
        $goodsId = $detail["id"];
        $originalAmount = $detail["marketPrice"];
        $orderStatus = 1;
        if($goodsIdstr){
            $goodsAll = explode(",", $goodsIdstr);
        }
        $referer = "webpc";
        //生成订单
        dump([$uid, $paymentType, $name, $goodsId, $goodsAll, $orderAmount,
            $originalAmount, $discountAmount, $couponSn, $groupCouponId, $orderStatus, $referer, $userNotes]);
        list($orderId, $orderNo) = $orderService->add($uid, $paymentType, $name, $goodsId, $goodsAll, $orderAmount,
            $originalAmount, $discountAmount, $couponSn, $groupCouponId, $orderStatus, $referer, $userNotes);

        if($this->error()->has()){
            $error = $this->error()->getLast();
            return $this->showMsg($error);
        }

        $body = $orderService->toPay($paymentType, $orderNo, $name, $orderAmount);
        if($this->error()->has()){
            $error = $this->error()->getLast();
            return $this->showMsg($error);
        }

        $data = [];
        $data['body'] = $body;
        $data['orderNo'] = $orderNo;
        if($paymentType == 1){//支付宝
            return $this->render('@WebsiteBundle/order/payRedirect.html.twig', $data);
        }else if($paymentType == 2){//微信
            return $this->render('@WebsiteBundle/order/wxpaycode.html.twig', $data);
        }else{
            //免费
            if($orderAmount == 0){
                $orderService->orderSuccessFreeOrder($orderNo);
            }
            return $this->render('@WebsiteBundle/order/payFreesuccess.html.twig', $data);
        }
    }


    /**
     * 微信支付成功检查
     * @Route("/my/do/wxpaycheck/{orderNo}", name="app_order_wxpayCheck")
     */
    public function wxpayCheck($orderNo, WxpayService $wxpayService, OrderService $orderService){
        $result = $wxpayService->query($orderNo);
        //NOTPAY
        if(($result['return_code'] == "SUCCESS") && ($result['trade_state'] == "SUCCESS")){
            $rs = $orderService->orderSuccess($orderNo);
            if(!$rs) return $this->responseSuccess(["status"=>0]);
            return $this->responseSuccess(["status"=>1]);
        }
        return $this->responseSuccess(["status"=>0]);
    }


    /**
     * 支付成功返回模式
     * @Route("/my/paysuccess/{orderNo}", name="app_order_paysuccess")
     */
    public function paysuccessAction($orderNo){
        $data = [];
        return $this->render('@WebsiteBundle/order/paysuccess.html.twig', $data);
    }

    /**
     * 支付宝购买回调 成功返回模式
     * @Route("/my/buyreturn/{orderNo}", name="app_order_buyreturn")
     */
    public function buyreturnAction($orderNo, Request $request, OrderService $orderService){
        if(!$orderNo){
            return $this->showMsg("参数错误");
        }

        if(!$orderService->checkSign($request->query->all())){
            return $this->showMsg("签名错误!");
        }
        $check = $orderService->orderSuccess($orderNo);

        if(!$check){
            return $this->showMsg($this->error()->getLast());
        }
        $data = [];
        return $this->render('@WebsiteBundle/order/paysuccess.html.twig', $data);
    }

}
