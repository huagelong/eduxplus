<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/11 19:42
 */

namespace Eduxplus\WebsiteBundle\Security;


use Eduxplus\WebsiteBundle\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessHandle implements LogoutSuccessHandlerInterface
{


    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @return Response never null
     */
    public function onLogoutSuccess(Request $request)
    {
        $request->cookies->remove(UserService::LOGIN_TOKEN);

        $goto = $request->get("goto");
        $url = $goto?$goto:"/";

        return new RedirectResponse($url);
    }
}
