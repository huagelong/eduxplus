<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace Eduxplus\ApiBundle\EventSubscriber;


use Eduxplus\CoreBundle\Lib\Base\ApiBaseService;
use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Service\RedisService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Exception;

class ControllerSubscriber implements EventSubscriberInterface
{
    protected $redisService;
    protected $apiBaseService;

    public function __construct(RedisService $redisService, ApiBaseService $apiBaseService)
    {
        $this->redisService = $redisService;
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
        if (!$event->isMainRequest()) {
            return true;
        }
        $request = $event->getRequest();
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        //api
        if ($controller instanceof BaseApiController) {
            // return true;
            $xsign = $this->getXsign($request);
            $debug = $request->headers->get("X-AUTH-DEBUG");
            $clientId = $request->headers->get('X-AUTH-CLIENT-ID');
            $clientId = $clientId ? strtolower($clientId) : "";
            // $body = $request->getContent();
            $contentType = $request->getContentType();
            $contentType = strtolower($contentType);

            if (!$xsign || !$clientId) {
                throw new Exception("X-AUTH-* Required!");
            }

            $clientIds = $this->apiBaseService->getConfig("app.clientId");
            if (!isset($clientIds[$clientId])) throw new Exception("X-AUTH-CLIENT-ID Authentication failed!");
            $salt = $clientIds[$clientId];

            $env = $this->apiBaseService->getEnv();
            if (($env === 'dev') && $debug) {
                return true;
            }

            $timestamp = $xsign["timestamp"];
            $nonce = $xsign["nonce"];
            $sign = $xsign["sign"];
    
            if(!$timestamp||!$nonce||!$sign){
                throw new Exception("Global parameters cannot be null!");
            }

            //sign 检查
            $redisKey = "preRequest:{$clientId}:{$nonce}";
            //时间阀值,重新请求
            $diffTime = 60;//秒
            if((time()-$timestamp) > $diffTime){
                throw new Exception("Bad request!");
            }
            //重复请求,幂等性
            if(!$this->redisService->setNxEx($redisKey, 1, $diffTime)){
                throw new Exception("Bad request!");
            }
    
            //sign 对比
            $localSign = md5($salt.$timestamp.$nonce);
            if($sign != $localSign){
                throw new Exception("Access denied!");
            }

            return true;
        }

        return true;
    }


    public function getXsign($request)
    {
        $signHeader = $request->headers->get("X-AUTH-SIGN");
        if(!$signHeader){
            $timestamp = $request->get("timestamp");
            $nonce = $request->get("nonce");
            $sign = $request->get("sign");
        }else{
            $signArr = json_decode($signHeader, true);
            $timestamp = isset($signArr["timestamp"])?$signArr["timestamp"]:"";
            $nonce = isset($signArr["nonce"])?$signArr["nonce"]:"";
            $sign = isset($signArr["sign"])?$signArr["sign"]:"";
        }
        return [
            "timestamp"=>$timestamp,
            "nonce"=>$nonce,
            "sign"=>$sign
        ];
    }

}
