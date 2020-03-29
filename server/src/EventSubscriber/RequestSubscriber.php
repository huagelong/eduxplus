<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace App\EventSubscriber;


use App\Bundle\AdminBundle\Service\MenuService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use App\Bundle\AppBundle\Lib\Base\BaseApiController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Stopwatch\Stopwatch;

class RequestSubscriber implements EventSubscriberInterface
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
            KernelEvents::REQUEST=>['onKernelRequest', 9999],
            KernelEvents::CONTROLLER => ['onKernelController', 9999],
            KernelEvents::RESPONSE=>['onKernelResponse', -9999],
            KernelEvents::VIEW=>['onKernelView', 99999]
        ];
    }

    public function onKernelView(ViewEvent $event){

    }


    public function onKernelRequest(RequestEvent $event){
        $this->stopwatch->start('event:elapsedTime');
    }

    public function onKernelResponse(ResponseEvent $event){
        $response = $event->getResponse();
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        //判断是否是json
        if($event->getRequest()->getRequestFormat() == 'json'){
            $content = json_decode($content, true);
            $responseData = [];
            $event = $this->stopwatch->stop('event:elapsedTime');
            $stopwatch = (string) $event;
            if(!isset($content['code'])){
                $responseData['code'] = $statusCode;
                $responseData['message']="";
                $responseData['data'] = $content;
                $responseData['stopwatch'] = $stopwatch;
            }else{
                $responseData = $content;
                $responseData['data'] = (object) null;
                $responseData['stopwatch'] = $stopwatch;
            }
            $response->setContent(json_encode($responseData, true));
        }
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

        if ($controller instanceof BaseAdminController) {
            $route = $request->get("_route");
            $session = $request->getSession();
            $session->set("_route", $route);
            $uid = $this->menuService->getUid();
            //权限验证
            $allMenu = $this->menuService->getMyMenuUrl($uid);
            if(!in_array($route, $allMenu)){
                throw new AccessDeniedException("Access Denied!");
            }
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
