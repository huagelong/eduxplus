<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace Eduxplus\ApiBundle\Controller;

use Eduxplus\CoreBundle\Entity\BaseUser;
use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Eduxplus\CoreBundle\Lib\Service\Auth\WxMiniService;
use Eduxplus\WebsiteBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Service\ValidateService;
use Eduxplus\WebsiteBundle\Service\GlobService;

/**
 * @package Eduxplus\ApiBundle\Controller
 */
class AuthController extends BaseApiController
{
    /**
     * @Rest\Post("/login", name="api_auth_login")
     *
     */
    public function login(Request $request,  UserPasswordEncoderInterface $passwordEncoder)
    {
    }


    /**
     * 退出
     * @Route("/logout", name="api_auth_logout")
     */
    public function logout()
    {
    }

    /**
     * 获取微信小程序openid
     * @Rest\Post("/wxMiniLogin", name="api_auth_wxMiniLogin")
     */
    public function wxMiniLoginAction(Request $request, WxMiniService $wxMiniService)
    {
        $code = $request->get("code");
        $source =  $request->headers->get('X-AUTH-CLIENT-ID');
        $data = $wxMiniService->login($code, $source);
        return $data;
    }

    /**
     * 获取微信小程序注册
     * @Rest\Post("/wxMiniReg", name="api_auth_wxMiniReg")
     */
    public function wxMiniRegAction(
        Request $request,
        WxMiniService $wxMiniService,
        UserService $userService,
        ValidateService $validateService,
        GlobService $globService
    ) {
        $nickname = $request->get("nickname");
        $avatarUrl = $request->get("avatarUrl");
        $openId = $request->get("openId");
        $sex = $request->get("gender");
        $source =  $request->headers->get('X-AUTH-CLIENT-ID');
        $mobile = $request->get("mobile");
        $code = $request->get("code");

        if (!$nickname) return $this->responseError("昵称不能为空!");
        if (!$avatarUrl) return $this->responseError("头像不能为空!");
        if (!$openId) return $this->responseError("openid 错误!");

        if (!$mobile) return $this->responseError("手机号码不能为空!");
        if (!$code) return $this->responseError("短信验证码不能为空!");

        if (!$validateService->mobileValidate($mobile)) return $this->responseError("手机号码格式错误!");
        //检查手机号码是否存在
        if ($userService->checkMobileExist($mobile)) return $this->responseError("手机号码已存在!");

        $check =  $globService->checkSmsCaptcha($code, $mobile, "appBindMobile");
        if (!$check) return $this->responseError("短信验证码验证失败!");

        $token = $wxMiniService->registerAndLogin($nickname, $avatarUrl, $openId, $sex, $mobile,$source);

        return ["token" => $token];
    }

}
