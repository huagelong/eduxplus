<?php
/**
 * User: Peter Wang
 * Date: 17/2/3
 * Time: 下午6:02
 */

namespace Lib\Service;

use Lib\Base\BaseService;
use Trensy\Support\ImgCaptcha;

final class CaptchaService extends BaseService
{
    const IMG_CAPTCHA = "IMG_CAPTCHA";

    /**
     * 获取验证码
     * @return array
     */
    public function get($type='')
    {
        $obj = new ImgCaptcha(100, 50, 4);
        $font = APPLICATION_PATH."/Lib/Service/fonts/SourceCodePro-Light.ttf";
        $obj->setFont($font);
        list($num, $source, $header) = $obj->create();
        $this->session()->set(self::IMG_CAPTCHA.$type, $num);
        return [$source, $header];
    }

    /**
     * 检查验证码
     *
     * @param $str
     * @return bool
     */
    public function check($str, $type='')
    {
        $session = $this->session()->get(self::IMG_CAPTCHA.$type);
        return $session == $str;
    }

    /**
     * 清楚验证码
     * @param string $type
     */
    public function clear($type='')
    {
        $this->session()->del(self::IMG_CAPTCHA.$type);
    }

}