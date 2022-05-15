<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/26 09:57
 */

namespace Eduxplus\WebsiteBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\CoreBundle\Lib\Service\Base\SmsService;
use Eduxplus\CoreBundle\Lib\Service\CacheService;
use Eduxplus\CoreBundle\Lib\Service\CaptchaService;
use Eduxplus\EduxBundle\Entity\MallMobileSmsCode;

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
       $key = self::SMS_KEY.":".$mobile.":".$type;
       $this->cacheService->set($key, $value, 70);

       if(!$this->checkTimes($mobile)) return false;
       $result  = $this->smsService->sendCaptcha($mobile, $value);
       if(!$result) return false;
       //保存
       $model = new MallMobileSmsCode();
       $model->setType($type);
       $model->setCode($value);
       $model->setMobile($mobile);
       $this->db()->save($model);
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
        $key = self::SMS_TIME_KEY.":".date('Y-m-d').":".$mobile;
        $times = (int) $this->cacheService->get($key);
        $smsTimes = $this->getOption("app.sms.times");
        if($times > ($smsTimes-1))  return $this->error()->add("已超过当天准许该手机发送短信的最大次数!");
        $time = strtotime(date('Y-m-d')." 23:59:59");
        $this->cacheService->setExpireAt($key,$times+1, $time);
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
        $key = self::SMS_KEY.":".$mobile.":".$type;
        $oldCode = $this->cacheService->get($key);
        if(!$oldCode) return $this->error()->add("短信验证码错误或者已过期！");
        return $oldCode == $code;
    }



}
