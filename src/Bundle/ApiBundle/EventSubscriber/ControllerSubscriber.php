<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace App\Bundle\ApiBundle\EventSubscriber;


use App\Bundle\AppBundle\Lib\Base\ApiBaseService;
use App\Bundle\AppBundle\Lib\Base\BaseApiController;
use App\Bundle\AppBundle\Lib\Base\BaseService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
            $clientId = $clientId ? strtolower($clientId) : "";
            $body = $request->getContent();
            $contentType = $request->getContentType();
            $contentType = strtolower($contentType);

            if (!$sign || !$clientId) {
                throw new AuthenticationException("X-AUTH-* Required!");
            }

            $clientIds = $this->apiBaseService->getConfig("app.clientId");
            if (!isset($clientIds[$clientId])) throw new AuthenticationException("X-AUTH-CLIENT-ID Authentication failed!");
            $salt = $clientIds[$clientId];

            $env = $this->apiBaseService->getEnv();
            if (($env === 'dev') && $debug) {
                return true;
            }

            $realSign = base64_decode($sign);
            list($time, $sign) = json_decode($realSign, true);
            $now = time();

            if (($now - $time) > 600) {
                throw new AuthenticationException("Api Request Expired");
            }
            if (!$debug) {
                $str = base64_decode($body);
            } else {
                $str = $body;
            }

            if (strpos($contentType, 'multipart/form-data') !== false) {
                $str = "";
                $body = "";
            }

            $checkSign = md5($str . $salt . $time);

            if ($sign != $checkSign) {
                $checkSign = md5($body . $salt . $time);
            }

            if ($sign != $checkSign) throw new AuthenticationException("Permission denied!");

            return true;
        }

        return true;
    }
}
