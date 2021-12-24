<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace Eduxplus\CoreBundle\EventSubscriber;


use Eduxplus\CoreBundle\Bundle\AdminBundle\Service\MenuService;
use Eduxplus\CoreBundle\Lib\Service\JsonResponseService;
use Eduxplus\CoreBundle\Lib\Service\ProjectService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class RequestSubscriber implements EventSubscriberInterface
{
    protected $stopwatch;
    protected $serializer;

    public function __construct(Stopwatch $stopwatch, SerializerInterface $serializer)
    {
        $this->stopwatch = $stopwatch;
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 30],
            KernelEvents::REQUEST => ['onKernelRequest', 9999],
            KernelEvents::RESPONSE => ['onKernelResponse', -9999]
        ];
    }


    public function onKernelView(ViewEvent $event): void
    {
        $response = $event->getResponse();
        if(!$response){
            $content = $event->getControllerResult();
            if(is_object($content)){
                $json = $this->serializer->serialize($content, 'json');
                $content = json_decode($json, true);
            }
            $event = $this->stopwatch->stop('event:elapsedTime');
            $stopwatch = (string) $event;
            $responseData = JsonResponseService::format(Response::HTTP_OK, $content, $stopwatch);
            $jsonResponse = new Response();
            $jsonResponse->headers->set('Content-Type', "application/json");
            //全部转驼峰
            $jsonResponse->setContent(json_encode($responseData, JSON_UNESCAPED_UNICODE));
            $event->setResponse($jsonResponse);
        }
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
        //multipart/form-data; boundary=----WebKitFormBoundarycbLP7rBjKmGgXOKK
        // $contentType = $event->getRequest()->headers->get("Content-Type");
        // var_dump($contentType);
        // $multipartCheck = strpos($contentType, 'multipart/form-data') === false;
        $route = $event->getRequest()->get("_route");
        $check = $route != "admin_glob_upload";
        // var_dump($multipartCheck);
        if ($check && (($event->getRequest()->getRequestFormat() == 'json') || (in_array("application/json", $event->getRequest()->getAcceptableContentTypes())))) {
            $content = json_decode($content, true);
            $event = $this->stopwatch->stop('event:elapsedTime');
            $stopwatch = (string) $event;
            $responseData = JsonResponseService::format($statusCode, $content, $stopwatch);
            $response->headers->set('Content-Type', "application/json");
            $response->setContent(json_encode($responseData, JSON_UNESCAPED_UNICODE));
            $response->setStatusCode(200); //强制转为200
        }
    }
}
