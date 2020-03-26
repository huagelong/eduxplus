<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 09:59
 */

namespace App\Bundle\CenterBundle\Lib\Service;

use App\Bundle\CenterBundle\Lib\Base\BaseService;

class CaptchaService extends BaseService
{
    const IMG_CAPTCHA = "IMG_CAPTCHA";

    /**
     * 获取验证码
     * @return array
     */
    public function get($session, $type='')
    {
        $obj = new ImgCaptchaService(100, 50, 4);
        $path = $this->getParameter("app.path");
        $font = dirname(__FILE__)."/fonts/SourceCodePro-Light.ttf";
        $obj->setFont($font);
        list($num, $source, $header) = $obj->create();
        $session->set(self::IMG_CAPTCHA.$type, $num);
        return [$source, $header];
    }

    /**
     * 检查验证码
     *
     * @param $str
     * @return bool
     */
    public function check($session,$str, $type='')
    {
        $sessionNum = $session->get(self::IMG_CAPTCHA.$type);
        return $sessionNum == $str;
    }

    /**
     * 清楚验证码
     * @param string $type
     */
    public function clear($session, $type='')
    {
        $session->remove(self::IMG_CAPTCHA.$type);
    }

}
