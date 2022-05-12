<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace Eduxplus\CoreBundle\EventSubscriber;


use Eduxplus\CoreBundle\Service\MenuService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Stopwatch\Stopwatch;

class ControllerSubscriber implements EventSubscriberInterface
{


    protected $stopwatch;
    protected $menuService;
    protected $containerBuilder;

    public function __construct(Stopwatch $stopwatch, MenuService $menuService)
    {
        $this->stopwatch = $stopwatch;
        $this->menuService = $menuService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 9900],
        ];
    }


    public function onKernelController(ControllerEvent $event)
    {
        if (!$event->isMainRequest()) {
            return true;
        }
        $request = $event->getRequest();
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }
        //admin
        if ($controller instanceof BaseAdminController) {
            $route = $request->get("_route");
            $session = $request->getSession();
            $session->set("_route", $route);
            $uid = $controller->getUid();
            //权限验证
            if ($uid) {
                //判断是否是全局权限
                $menuInfo = $this->menuService->getMenuByRoute($route);
                if (!$menuInfo) {
                    throw new AuthenticationException("没有权限!");
                } else {
                    if (!$menuInfo['isGlobal']) {
                        $allMenu = $this->menuService->getMyMenuUrl($uid);
                        if (!in_array($route, $allMenu)) {
                            throw new AuthenticationException("没有权限!");
                        }
                    }
                    //记录日志
                    $this->menuService->addActionLog($uid, $request);
                }
            }
        }

        return true;
    }
}
