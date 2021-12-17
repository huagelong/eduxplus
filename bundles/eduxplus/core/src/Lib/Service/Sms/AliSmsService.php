<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 09:59
 */

namespace Eduxplus\CoreBundle\Lib\Service\Sms;


use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Psr\Log\LoggerInterface;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

/**
 * 阿里云短信服务
 *
 * Class AliSmsService
 * @package Eduxplus\CoreBundle\Lib\Service
 */
class AliSmsService extends BaseService
{

    protected $config;


    protected function initialization(){
        $accessKeyId = $this->getOption("app.aliyun.accesskeyId");
        $accesskeySecret = $this->getOption("app.aliyun.accesskeySecret");
        AlibabaCloud::accessKeyClient($accessKeyId, $accesskeySecret)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
    }

    /**
     * 发送验证码
     * @param $code
     */
    public function sendCaptcha($mobile, $code){
        $smsCode = $this->getOption("app.aliyun.sms.captcha.code");
        $templateParam = "{\"code\":{$code}}";
        return $this->send($mobile, $smsCode, $templateParam);
    }

    public function send($mobile, $smsCode, $templateParam){
        $this->initialization();
        $smsSign = $this->getOption("app.aliyun.smsSign");
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $mobile,
                        'SignName' => $smsSign,
                        'TemplateCode' => $smsCode,
                        'TemplateParam' => $templateParam,
                    ],
                ])
                ->request();
            $result = $result->toArray();
            if($result['Code'] === 'OK') return true;
            return $this->error()->add($result['Message']);
        } catch (ClientException $e) {
            return $this->error()->add($e->getErrorMessage());
        } catch (ServerException $e) {
            return $this->error()->add($e->getErrorMessage());
        }
    }
}
