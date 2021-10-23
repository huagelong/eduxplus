<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/12 11:43
 */

namespace App\EventListener;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Bundle\AppBundle\Lib\Service\JsonResponseService;
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
        if (!$event->isMasterRequest()) return true;
        $exception = $event->getThrowable();
        $msg = $exception->getMessage();
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : $exception->getCode();
        if (($event->getRequest()->getRequestFormat() == 'json') || (in_array("application/json", $event->getRequest()->getAcceptableContentTypes()))) {
            $this->logger->error($exception->getTraceAsString());
            $responseData = JsonResponseService::genData([], $statusCode, $msg);
            $response = new JsonResponse($responseData);
            $response->setStatusCode(200); //强制转为200
            return $event->setResponse($response);
        }
    }
}
