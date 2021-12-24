<?php


/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 14:07
 */

namespace Eduxplus\ApiBundle\Controller;

use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Eduxplus\WebsiteBundle\Service\GlobService;
use Eduxplus\CoreBundle\Lib\Service\ValidateService;

class GlobController extends BaseApiController
{
    /**
     * 短信验证码
     * @Rest\Post("/sendCaptcha", name="api_glob_sendCaptcha")
     */
    public function sendCaptchaAction(Request $request, ValidateService $validateService, GlobService $globService)
    {
        $type = $request->get('type');
        $mobile = $request->get('mobile');
        if (!$type || !$mobile) return $this->responseError("参数缺失!");
        if (!$validateService->mobileValidate($mobile)) return $this->responseError("手机号码格式错误!");

        $globService->sendCaptcha($mobile, $type);
        if ($this->error()->has()) {
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("验证码发送成功!");
    }
}
