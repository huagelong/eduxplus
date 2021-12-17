<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/9 10:15
 */

namespace Eduxplus\CoreBundle\Lib\Service\Pay;


use Eduxplus\CoreBundle\Lib\Base\BaseService;
use EasyWeChat\Factory;

/**
    https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=3_1
**/
class WxpayService extends BaseService
{
    /**
     * 统一下单
     * @param $subject
     * @param $outTradeNo
     * @param $totalAmount
     * @param $notifyUrl
     * @param string $openid
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pagePay($subject, $outTradeNo, $totalAmount, $notifyUrl, $tradeType="NATIVE", $productId="", $openid=""){
        $config = [
            'body' => $subject,
            'out_trade_no' => $outTradeNo,
            'total_fee' => $totalAmount,
            'notify_url' => $notifyUrl, // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => $tradeType, // 请对应换成你的支付方式对应的值类型 ,JSAPI,
        ];
        if($openid) $config['openid'] = $openid; //用户标识
        if($productId == "NATIVE") $config['product_id'] = $productId;
        $result = $this->getClient()->order->unify($config);
//        var_dump($result);
        if($result["return_code"] != "SUCCESS"){
            return $this->error()->add($result["return_msg"]);
        }
        //result_code
        return $result;
    }

    public function getClient(){
        $appid = $this->getOption("app.wxpay.appid");
        $mchid = $this->getOption("app.wxpay.mchid");
        $key = $this->getOption("app.wxpay.key");
        $notifyUrl = $this->getOption("app.wxpay.notifyrl");
        $sanbox = $this->getOption("app.wxpay.sandbox")?true:false;

        $config = [
            // 必要配置
            'app_id'             => $appid,
            'mch_id'             => $mchid,
            'key'                => $key,   // API 密钥
            'sandbox' => $sanbox,
            'notify_url'         => $notifyUrl,     // 你也可以在下单时单独设置来想覆盖它
        ];
        $app = Factory::payment($config);
        return $app;
    }

    /**
     * 查询
     * @param $outTradeNo
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function query($outTradeNo){
        return $this->getClient()->order->queryByOutTradeNumber($outTradeNo);
    }

    /**
     * 关闭
     * @param $outTradeNo
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function close($outTradeNo){
        return $this->getClient()->order->close($outTradeNo);
    }

}
