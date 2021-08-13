<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/17 17:30
 */

namespace App\Bundle\AppBundle\Lib\Service\Sms;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use Qcloud\Sms\SmsSingleSender;

class TengxunSmsService extends BaseService
{

    public function sendCaptcha($mobile, $code){
        $templateId = $this->getOption("app.tengxunyun.sms.recaptcha.templateId");
        $params = [$code];
        return $this->send($mobile, $templateId, $params);
    }

    public function send($mobile, $templateId, $params){
        // 短信应用SDK AppID
        $appid = $this->getOption("app.tengxunyun.sms.appid"); // 1400开头
        $appkey = $this->getOption("app.tengxunyun.sms.appkey");

        $smsSign = ""; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`
        try {
            $ssender = new SmsSingleSender($appid, $appkey);
            $result = $ssender->sendWithParam("86",$mobile, $templateId,
                $params, $smsSign, "", "");
            $result = json_decode($result, true);
            return $result['result']==0?true:false;
        } catch (\Exception $e) {
            return $this->error()->add($e->getMessage());
        }
    }

}
