<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/19 10:02
 */

namespace App\EventSubscriber;


use App\Bundle\AdminBundle\Service\MenuService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
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
            KernelEvents::RESPONSE=>['onKernelResponse', -9999]
        ];
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
                $message = isset($content['_message'])?$content['_message']:"";
                if(isset($content['_message'])) unset($content['_message']);
                $responseData['code'] = $statusCode;
                $responseData['message']=$message;
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
}
