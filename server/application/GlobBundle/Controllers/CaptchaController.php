<?php
/**
 * 验证码
 */

namespace Glob\Controllers;

final class CaptchaController extends BaseController
{

    /**
     * @Inject
     * @var \Lib\Service\CaptchaService
     */
    public $captchaService;

    public function whiteActions(){
        return ['recaptcha', 'checkcaptcha'];
    }

    /**
     * 图形验证码
     */
    public function recaptcha($type='')
    {
        list($source, $header) = $this->captchaService->get($type);

        if($header){
            foreach ($header as $v){
                $this->response->headerStr($v);
            }
        }
        $this->response->end($source);
    }

    /**
     * 检查图形验证码
     */
    public function checkcaptcha($type='')
    {
        $vl = $this->getRequest()->get("vl");
        if(!$vl) return $this->response([],self::RESPONSE_NORMAL_ERROR_CODE);
        $check = $this->captchaService->check($vl, $type);
        if(!$check) $this->response([],self::RESPONSE_NORMAL_ERROR_CODE);
        return $this->response();
    }
}
