<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/18 10:40
 */

namespace App\Bundle\AppBundle\Security;

use App\Bundle\AppBundle\Lib\Service\ValidateService;
use App\Bundle\AppBundle\Service\UserService;
use App\Exception\NeedLoginException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class MobileAuthenticator extends AbstractFormLoginAuthenticator
{

    protected $userService;
    protected $urlGenerator;
    protected $validateService;

    public function __construct(UserService $userService,
                                UrlGeneratorInterface $urlGenerator,
                                ValidateService $validateService
)
    {
        $this->urlGenerator = $urlGenerator;
        $this->userService = $userService;
        $this->validateService = $validateService;
    }


    public function start(Request $request, AuthenticationException $authException = null)
    {
        if(($request->getRequestFormat() == 'json') || (in_array("application/json", $request->getAcceptableContentTypes()))){
            if($authException->getCode()=="401"){
                throw new AuthenticationException($authException->getMessage());
            }else{
                throw new AuthenticationException("请先登录!");
            }
        }else{
            $url = $this->getLoginUrl();
            return new RedirectResponse($url);
        }
    }

    /**
     * Does the authenticator support the given Request?
     *
     * If this returns false, the authenticator will be skipped.
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return ('app_logindo' === $request->attributes->get('_route')) && $request->isMethod('POST');
    }

    /**
     * Get the authentication credentials from the request and return them
     * as any type (e.g. an associate array).
     *
     * Whatever value you return here will be passed to getUser() and checkCredentials()
     *
     * For example, for a form login, you might:
     *
     *      return [
     *          'username' => $request->request->get('_username'),
     *          'password' => $request->request->get('_password'),
     *      ];
     *
     * Or for an API token that's on a header, you might use:
     *
     *      return ['api_key' => $request->headers->get('X-API-TOKEN')];
     *
     * @return mixed Any non-null value
     *
     * @throws \UnexpectedValueException If null is returned
     */
    public function getCredentials(Request $request)
    {
        $mobile = $request->get("mobile");
        $code = $request->get("code");
        $goto = $request->get("goto");

        if(!$mobile) throw new AuthenticationException("手机号码不能为空！");
        if(!$code) throw new AuthenticationException("短信验证码不能为空！");
        if(!$this->validateService->mobileValidate($mobile)) throw new AuthenticationException("手机号码格式错误！", 401);

        $request->cookies->set("site_login_mobile", $mobile);
        $request->cookies->set("site_login_goto", $goto);

        return [$mobile, $code, $goto];
    }

    /**
     * Return a UserInterface object based on the credentials.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * You may throw an AuthenticationException if you wish. If you return
     * null, then a UsernameNotFoundException is thrown for you.
     *
     * @param mixed $credentials
     *
     * @throws AuthenticationException
     *
     * @return UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        list($mobile, $code, $goto) = $credentials;

        if(!$this->userService->checkSmsCaptcha($code, $mobile, "sitelogin")){
            throw new AuthenticationException($this->userService->error()->getLast());
        }

        list($token, $uid) = $this->userService->checkLogin($mobile, "html", "pc");

        if($this->userService->error()->has()){
            throw new AuthenticationException($this->userService->error()->getLast(), 401);
        }

        if(!$token) throw new AuthenticationException("登录失败!",401);

        $userInfo = $this->userService->getUserObj($uid);
        $time = 60 * 60 * 24 * 30;
        $expire = time() + $time;
        $cookie = new Cookie(UserService::LOGIN_TOKEN, $userInfo->getHtmlToken(), $expire);
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->sendHeaders();
        return $userInfo;
    }

    /**
     * Returns true if the credentials are valid.
     *
     * If false is returned, authentication will fail. You may also throw
     * an AuthenticationException if you wish to cause authentication to fail.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * @param mixed $credentials
     *
     * @return bool
     *
     * @throws AuthenticationException
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 401 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     *
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw  new AuthenticationException($exception->getMessage(), 401);
//        throw  new AuthenticationException("请先登录!");
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param string $providerKey The provider (i.e. firewall) key
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $goto = $request->get("goto");
        $gotoCookie = $request->cookies->get("site_login_goto");
        $url = $goto?$goto:($gotoCookie?$gotoCookie:"/");
        $data = [];
        $data['code'] = 200;
        $data['_url'] = $url;
        $response =  new JsonResponse($data);

        return $response;
    }

    /**
     * Does this method support remember me cookies?
     *
     * Remember me cookie will be set if *all* of the following are met:
     *  A) This method returns true
     *  B) The remember_me key under your firewall is configured
     *  C) The "remember me" functionality is activated. This is usually
     *      done by having a _remember_me checkbox in your form, but
     *      can be configured by the "always_remember_me" and "remember_me_parameter"
     *      parameters under the "remember_me" firewall key
     *  D) The onAuthenticationSuccess method returns a Response object
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return true;
    }

    protected function getLoginUrl()
    {
        $goto = $this->userService->request()->getRequestUri();
        $gotoStr = $goto?"?goto=".$goto:"";
        return $this->urlGenerator->generate('app_login').$gotoStr;
    }
}
