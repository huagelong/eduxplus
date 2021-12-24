<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace Eduxplus\CoreBundle\EventSubscriber;


use Eduxplus\CoreBundle\Lib\Service\JsonResponseService;
use Eduxplus\CoreBundle\Lib\Service\ProjectService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Stopwatch\Stopwatch;

class RequestSubscriber implements EventSubscriberInterface
{
    protected $stopwatch;

    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 9999],
            KernelEvents::RESPONSE => ['onKernelResponse', -9999]
        ];
    }



    public function onKernelRequest(RequestEvent $event)
    {
        $this->stopwatch->start('event:elapsedTime');
        //设置projectId, 可以从redis获取
        ProjectService::$projectId = 1;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        //判断是否是json
        $route = $event->getRequest()->get("_route");
        $check = $route != "admin_glob_upload";
        // var_dump($multipartCheck);
        if ($check && (($event->getRequest()->getRequestFormat() == 'json') || (in_array("application/json", $event->getRequest()->getAcceptableContentTypes())))) {
            $content = json_decode($content, true);
            $event = $this->stopwatch->stop('event:elapsedTime');
            $stopwatch = (string) $event;
            $responseData = JsonResponseService::format($statusCode, $content, $stopwatch);
            $response->setContent(json_encode($responseData, JSON_UNESCAPED_UNICODE));
            $response->setStatusCode(200); //强制转为200
        }
    }
}
