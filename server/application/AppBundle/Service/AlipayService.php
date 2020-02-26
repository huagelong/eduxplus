<?php

namespace Site\Service;

use Lib\Base\BaseService;
use Payment\Common\PayException;

final class AlipayService extends BaseService
{
    /**
     * @var \Lib\Service\PayService
     */
    public $payService;

    /**
     * 支付宝入口
     * @throws \Exception
     */
    /**
     * @param $amount float 商品金额
     * @param $tradeNo string 订单号 AwSDF2323
     * @param $courseTitle string 商品的标题
     * @param string $descr string 商品的描述
     * @throws \Trensy\Http\Exception\ContextErrorException
     */
    public function payCharge($amount, $tradeNo, $courseTitle, $descr = '')
    {
        try {
            $payUrl = $this->payService->createAlipayWeb($amount, $tradeNo, $courseTitle, $descr);
            $this->getResponse()->redirect($payUrl);
        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
        }
    }

    /**
     * 支付宝回调
     * @param $out_trade_no
     * @return bool
     */
    public function callBack($out_trade_no)
    {
        return $this->payService->query($out_trade_no);
    }

    /**
     * 支付宝异步通知
     */
    public function getWay()
    {
        #$ret = $this->payService->notify(array());
        #var_export($ret);
    }
}