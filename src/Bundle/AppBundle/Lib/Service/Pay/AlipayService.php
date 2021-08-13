<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/3 13:52
 */

namespace App\Bundle\AppBundle\Lib\Service\Pay;


use Alipay\EasySDK\Kernel\Config;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use App\Bundle\AppBundle\Lib\Base\BaseService;

/**
 * 支付宝支付接口
 * 1、开发者中心添加应用 https://openhome.alipay.com/platform/developerIndex.htm
 * 2、账号中心绑定appid  https://mrchportalweb.alipay.com/accountmanage/bind/appIdBindList
 * Class AlipayService
 * @package App\Bundle\AppBundle\Lib\Service\Pay
 */
class AlipayService extends BaseService
{
   protected function init()
    {
        $appId = $this->getOption("app.alipay.appid");
        $notifyUrl  = $this->getOption("app.alipay.notifyUrl");

        $merchantPrivateKey =  $this->getOption("app.alipay.merchantPrivateKey");
        $alipayPublicKey = $this->getOption("app.alipay.alipayPublicKey");
        $encryptKey  = $this->getOption("app.alipay.encryptKey");
        $isSandbox = $this->getOption("app.alipay.isSandbox");

        $options = new Config();
        $options->protocol = 'https';

        if(!$isSandbox){
            $options->gatewayHost = 'openapi.alipay.com';
        }else{
            $options->gatewayHost = 'openapi.alipaydev.com';
        }

        $options->signType = 'RSA2';
        $options->appId = $appId;

        $options->merchantPrivateKey = $merchantPrivateKey;
            //注：如果采用非证书模式，则无需赋值上面的三个证书路径，改为赋值如下的支付宝公钥字符串即可
        $options->alipayPublicKey = $alipayPublicKey;
        //可设置异步通知接收服务地址（可选）
        $options->notifyUrl = $notifyUrl;
        //可设置AES密钥，调用AES加解密相关接口时需要（可选）
        $options->encryptKey = $encryptKey;

        Factory::setOptions($options);
    }

    /**
     * 统一收单下单并支付页面接口 web端
     * @param $subject 订单标题
     * @param $outTradeNo 商户订单号
     * @param $totalAmount 订单总金额，单位为元，精确到小数点后两位
     * @param $returnUrl HTTP/HTTPS开头字符串
     */
    public function pagePay($subject, $outTradeNo, $totalAmount, $returnUrl){
        try {
            $this->init();
            $notifyUrl  = $this->getOption("app.alipay.notifyUrl");
            $result = Factory::payment()->page();
            if($notifyUrl){
                $result = $result->asyncNotify($notifyUrl);
            }
            $result = $result->pay($subject, $outTradeNo, $totalAmount, $returnUrl);
            $responseChecker = new ResponseChecker();
            if (!$responseChecker->success($result)) {
               return $this->error()->add("调用失败，原因：". $result->msg."，".$result->subMsg.PHP_EOL);
            }
            return $result->body;
        } catch (\Exception $e) {
            $this->error()->add("调用失败，". $e->getMessage(). PHP_EOL);
        }
    }

    /**
     * 查询订单信息
     * @param $outTradeNo
     * @return array
     */
    public function query($outTradeNo){
        try {
            $this->init();
            $result = Factory::payment()->common()->query($outTradeNo);
          //see  https://opendocs.alipay.com/apis/api_1/alipay.trade.query
            /**
            trade_status:  交易状态：WAIT_BUYER_PAY（交易创建，等待买家付款）、TRADE_CLOSED（未付款交易超时关闭，或支付完成后全额退款）、TRADE_SUCCESS（交易支付成功）、TRADE_FINISHED（交易结束，不可退款）
             */
            $arrResult =  $result->toMap();
            if($arrResult['code'] == 10000) return $arrResult;
            return $this->error()->add($arrResult['sub_msg']);
        }catch (\Exception $e) {
            $this->error()->add("调用失败，". $e->getMessage(). PHP_EOL);
        }
    }

    /**
     * 如果此订单用户支付失败，支付宝系统会将此订单关闭；如果用户支付成功，支付宝系统会将此订单资金退还给用户
     * 提交支付交易后调用【查询订单API】，没有明确的支付结果再调用【撤销订单API】。
     * @param $outTradeNo
     * @return array|bool
     */
    public function cancel($outTradeNo){
        try {
            $this->init();
            $result = Factory::payment()->common()->cancel($outTradeNo);
            $arrResult =  $result->toMap();
            if($arrResult['code'] == 10000) return $arrResult;
            return $this->error()->add($arrResult['sub_msg']);
        }catch (\Exception $e) {
            $this->error()->add("调用失败，". $e->getMessage(). PHP_EOL);
        }
    }


    /**
     * 超时为创建，先查询发现未支付，再操作
     * 用于交易创建后，用户在一定时间内未进行支付，可调用该接口直接将未付款的交易进行关闭。
     * @param $outTradeNo
     * @return array|bool
     */
    public function close($outTradeNo){
        try {
            $this->init();
            $result = Factory::payment()->common()->close($outTradeNo);
            $arrResult =  $result->toMap();
            if($arrResult['code'] == 10000) return $arrResult;
            return $this->error()->add($arrResult['sub_msg']);
        }catch (\Exception $e) {
            $this->error()->add("调用失败，". $e->getMessage(). PHP_EOL);
        }
    }


    /**
     * 验证签名
     * @param $sign
     * @param $requestData
     */
    public function checkSign($requestData){
        $this->init();
        return Factory::payment()->common()->verifyNotify($requestData);
    }

}
