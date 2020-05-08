<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace App\Bundle\ApiBundle\EventSubscriber;


use App\Bundle\AdminBundle\Service\MenuService;
use App\Bundle\AppBundle\Lib\Base\BaseApiController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Stopwatch\Stopwatch;

class ControllerSubscriber implements EventSubscriberInterface
{
    protected $stopwatch;
    protected $menuService;

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

        //api
        if ($controller instanceof BaseApiController) {
            $sign = $request->headers->get("X-AUTH-SIGN");
            $debug = $request->headers->get("X-AUTH-DEBUG");

            $env = $_SERVER['APP_ENV'];
            if(($env === 'dev') && $debug){
                return true;
            }

            if($sign){
                try {
                    $realSign = base64_decode($sign);
                    list($time, $sign) = json_decode($realSign, true);

                }catch (\Exception $e){
                    throw new AccessDeniedException("This api needs X-AUTH-SIGN");
                }
            }else{
                throw new AccessDeniedException("This api needs X-AUTH-SIGN");
            }
        }

        return true;
    }
}
