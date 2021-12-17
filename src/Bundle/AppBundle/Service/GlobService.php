<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/26 09:57
 */

namespace App\Bundle\AppBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\CoreBundle\Lib\Service\SmsService;
use Eduxplus\CoreBundle\Lib\Service\CacheService;
use Eduxplus\CoreBundle\Lib\Service\CaptchaService;
use Eduxplus\CoreBundle\Entity\MallMobileSmsCode;

class GlobService extends AppBaseService
{
    protected $captchaService;
    protected $cacheService;
    protected $smsService;

    const SMS_KEY = "SMS_KEY";
    const SMS_TIME_KEY = "SMS_TIME_KEY";

    public function __construct(CaptchaService $captchaService, CacheService $cacheService, SmsService $smsService)
    {
        $this->captchaService = $captchaService;
        $this->cacheService = $cacheService;
        $this->smsService = $smsService;
    }


    /**
     * 检查 图形验证码
     * @param $imgCode
     * @param $type
     * @return bool
     */
    public function checkImgCaptcha($imgCode, $type){
        $session = $this->session();
        $rs =  $this->captchaService->check($session, $imgCode, $type);
        $this->captchaService->clear($session, $type);
        return $rs;
   }


    /**
     * 发送短信验证码
     *
     * @param $mobile
     * @param $type
     * @return bool
     */
   public function sendCaptcha($mobile, $type){
       $value = mt_rand(100000,999999);
       $key = self::SMS_KEY."_".$mobile."_".$type;
       $this->cacheService->set($key, $value, 70);

       if(!$this->checkTimes($mobile)) return false;
       $result  = $this->smsService->sendCaptcha($mobile, $value);
       if(!$result) return false;
       //保存
       $model = new MallMobileSmsCode();
       $model->setType($type);
       $model->setCode($value);
       $model->setMobile($mobile);
       $this->save($model);
       return true;
   }

    /**
     * 检查短信每天发送次数
     *
     * @param $mobile
     * @return bool
     */
    protected function checkTimes($mobile)
    {
        $key = self::SMS_TIME_KEY."_".date('Y-m-d')."_".$mobile;
        $times = (int) $this->cacheService->get($key);
        $smsTimes = $this->getOption("app.sms.times");
        if($times > ($smsTimes-1))  return $this->error()->add("已超过当天准许该手机发送短信的最大次数!");
        $this->cacheService->set($key,$times+1, 86400);
        return true;
    }


    /**
     * 检查短信验证码
     *
     * @param $code
     * @param $mobile
     * @param $type
     * @return bool
     */
    public function checkSmsCaptcha($code, $mobile, $type)
    {
        $env = $this->getEnv();
        if($env == 'dev') return true;
        $key = self::SMS_KEY."_".$mobile."_".$type;
        $oldCode = $this->cacheService->get($key);
        if(!$oldCode) return $this->error()->add("短信验证码错误或者已过期！");
        return $oldCode == $code;
    }



}
