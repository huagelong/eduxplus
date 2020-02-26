<?php

namespace Site\Service;

use Lib\Base\BaseService;
use Payment\Common\PayException;

final class WxpayService extends BaseService
{
    /**
     * @var \Lib\Service\PayService
     */
    public $payService;

    /**
     * 微信扫码支付入口
     * @param $amount
     * @param $tradeNo
     * @param $courseTitle
     * @param string $descr
     * @return mixed|string
     */
    public function payCharge($amount, $tradeNo, $courseTitle, $descr = '')
    {
        try {
            $payUrl = $this->payService->createWx($amount, $tradeNo, $courseTitle, $descr);
            return array(
                'url' => $payUrl,
            );
        } catch (PayException $e) {
            return $e->errorMessage();
        }
    }

    /**
     * 微信回调
     * @param $out_trade_no
     * @return bool
     */
    public function callBack($out_trade_no)
    {
        return $this->payService->query($out_trade_no, 'wx_qr');
    }

    /**
     * 微信检测
     * @param $out_trade_no
     * @return string
     * @throws PayException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function check($out_trade_no)
    {
        return $this->payService->WxCheck($out_trade_no);
    }
}