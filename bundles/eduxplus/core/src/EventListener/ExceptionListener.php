<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/12 11:43
 */

namespace Eduxplus\CoreBundle\EventListener;


use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Service\JsonResponseService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    protected $baseService;
    protected $logger;

    public function __construct(BaseService $baseService, LoggerInterface $logger)
    {
        $this->baseService = $baseService;
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if (!$event->isMainRequest()) return true;
        $exception = $event->getThrowable();
        $this->logger->error($exception->getFile()."(".$exception->getLine().") ".$exception->getMessage());

        $pathPatterns = $this->baseService->getConfig("eduxplus_core.json_patterns");
        $requestUri = $this->baseService->request()->getRequestUri();
        $hasMatch = 0;
        if($pathPatterns){
            foreach ($pathPatterns as $regx){
                if(preg_match($regx, $requestUri)){
                    $hasMatch++;
                }
            }
        }
        $msg = $exception->getMessage();
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : $exception->getCode();
        if ($hasMatch) {
            if($statusCode == 0){
                $statusCode = 500;
            }
            $response = JsonResponseService::genData([], $statusCode, $msg);
            $response->setStatusCode(200); //强制转为200
            return $event->setResponse($response);
        }
    }
}
