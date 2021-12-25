<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 09:59
 */

namespace Eduxplus\CoreBundle\Lib\Service\Base;


use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Service\Sms\AliSmsService;
use Eduxplus\CoreBundle\Lib\Service\Sms\TengxunSmsService;
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
class SmsService extends BaseService
{
    protected $aliSmsService;
    protected $tengxunSmsService;

    public function __construct(AliSmsService $aliSmsService, TengxunSmsService $tengxunSmsService)
    {
        $this->aliSmsService = $aliSmsService;
        $this->tengxunSmsService =$tengxunSmsService;
    }

    /**
     * 发送验证码
     * @param $code
     */
    public function sendCaptcha($mobile, $code){
        $adapter = $this->getOption("app.sms.adapter");
        $adapter = $adapter?$adapter:1;
        if(1==$adapter){ //腾讯云
            return $this->tengxunSmsService->sendCaptcha($mobile, $code);
        }

        if(2 == $adapter){//阿里云
           return  $this->aliSmsService->sendCaptcha($mobile, $code);
        }
    }

    public function send($mobile, $smsCode, $templateParam){
        $adapter = $this->getOption("app.sms.adapter");
        $adapter = $adapter?$adapter:1;
        if(1==$adapter){ //腾讯云
            return  $this->tengxunSmsService->send($mobile, $smsCode, $templateParam);
        }

        if(2 == $adapter){//阿里云
            return  $this->aliSmsService->send($mobile, $smsCode, $templateParam);
        }
    }
}
