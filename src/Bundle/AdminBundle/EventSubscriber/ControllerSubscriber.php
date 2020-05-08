<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace App\Bundle\AdminBundle\EventSubscriber;


use App\Bundle\AdminBundle\Service\MenuService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
        if (!$event->isMasterRequest()) {
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
            $uid = $this->menuService->getUid();
            //权限验证
            if($uid){
                $allMenu = $this->menuService->getMyMenuUrl($uid);
                if(!in_array($route, $allMenu)){
                    throw new AccessDeniedException("没有权限!");
                }
            }
        }

        return true;
    }
}
