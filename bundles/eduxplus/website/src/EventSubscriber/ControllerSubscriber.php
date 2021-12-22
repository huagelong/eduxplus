<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace Eduxplus\WebsiteBundle\EventSubscriber;


use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\WebsiteBundle\Service\UserService;
use Eduxplus\CoreBundle\Exception\LoginExpiredException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ControllerSubscriber implements EventSubscriberInterface
{


    protected $containerBuilder;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 9900],
        ];
    }


    public function onKernelController(ControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return true;
        }
        $request = $event->getRequest();
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof BaseHtmlController) {
            $uid = $controller->getUid();
            $route = $request->get("_route");
            //退出操作不处理
            if($route == 'app_logout') return true;
            if($uid){ //自动登录
                $token = $request->cookies->get(UserService::LOGIN_TOKEN);

                if($token){
                    $user = $this->userService->getUserByToken($token, "html");
                    if(!$user){
                        //todo退出
                        if(($request->getRequestFormat() == 'json') || (in_array("application/json", $request->getAcceptableContentTypes()))){
                            throw new LoginExpiredException();
                        }else{
                            $logoutUrl = $this->userService->genUrl("app_logout")."?goto=".$this->userService->genUrl("app_login");
//                            print_r($logoutUrl);exit;
                            return (new RedirectResponse($logoutUrl))->send();
                        }
                    }
                }
            }
        }

        return true;
    }
}
