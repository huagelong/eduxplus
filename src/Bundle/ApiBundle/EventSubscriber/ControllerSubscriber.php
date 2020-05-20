<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace App\Bundle\ApiBundle\EventSubscriber;


use App\Bundle\ApiBundle\Service\ApiBaseService;
use App\Bundle\AppBundle\Lib\Base\BaseApiController;
use App\Bundle\AppBundle\Lib\Base\BaseService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ControllerSubscriber implements EventSubscriberInterface
{
    protected $baseService;
    protected $apiBaseService;

    public function __construct(BaseService $baseService, ApiBaseService $apiBaseService)
    {
        $this->baseService = $baseService;
        $this->apiBaseService = $apiBaseService;
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
            $clientId = $request->headers->get('X-AUTH-CLIENT-ID');
//            $token = $request->headers->get('X-AUTH-TOKEN');
            $clientId = $clientId?strtolower($clientId):"";

            if(!$sign || !$clientId) {
                throw new AccessDeniedException("X-AUTH-* Required!");
            }

            $clientIds = $this->baseService->getConfig("app.clientId");
            if(!isset($clientIds[$clientId])) throw new AccessDeniedException("X-AUTH-CLIENT-ID Authentication failed!");
            $salt = $clientIds[$clientId];

            $env = $_SERVER['APP_ENV'];
            if(($env === 'dev') && $debug){
                return true;
            }

            if($sign){
                try {
                    $realSign = base64_decode($sign);
                    list($time, $sign) = json_decode($realSign, true);
                    $now = time();
                    if(($now-$time)>600){
                        throw new AccessDeniedException("Api Request Expired");
                    }

                    $body = $request->getContent();
                    if(!$debug){
                        $str = base64_decode($body);
                    }else{
                        $str = $body;
                    }

                    $contentType = $request->getContentType();
                    $contentType = strtolower($contentType);
                    if(strpos($contentType,'multipart/form-data') !==false){
                        $str="";
                        $body="";
                    }

                    $checkSign = md5($str . $salt.$time);

                    if($sign != $checkSign){
                        $checkSign = md5($body . $salt.$time);
                    }

                    if($sign != $checkSign) throw new AccessDeniedException("Permission denied!");
                    return true;
                }catch (\Exception $e){
                    throw new AccessDeniedException($e->getMessage());
                }
            }else{
                throw new AccessDeniedException("This api needs X-AUTH-SIGN");
            }
        }

        return true;
    }
}
