<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 09:59
 */

namespace App\Bundle\AppBundle\Lib\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use Psr\Log\LoggerInterface;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

/**
 * 阿里云短信服务
 *
 * Class AliSmsService
 * @package App\Lib\Service
 */
class AliSmsService extends BaseService
{

    protected $config;


    protected function initialization(){
        $this->config = $this->getParameter("app.aliyun");

        AlibabaCloud::accessKeyClient($this->config['accesskeyId'], $this->config['accesskeySecret'])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
    }

    /**
     * 发送验证码
     * @param $code
     */
    public function sendCaptcha($mobile, $code){
        $this->initialization();
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
                        'SignName' => $this->config['smsSign'],
                        'TemplateCode' => $this->config['smsCode'],
                        'TemplateParam' => "{\"code\":{$code}}",
                    ],
                ])
                ->request();
            $result = $result->toArray();
            if($result['Code'] === 'OK') return true;
            throw new \Exception($result['Message']);
        } catch (ClientException $e) {
            throw new \Exception($e->getErrorMessage());
        } catch (ServerException $e) {
            throw new \Exception($e->getErrorMessage());
        }
    }
}
