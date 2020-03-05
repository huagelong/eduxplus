<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 20:41
 */

namespace App\CenterBundle\Lib\Base;

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface ;

class BaseService
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ContainerInterface
     */
    protected $parameterBag;


    public function __construct(LoggerInterface $logger, ContainerInterface $parameterBag )
    {
        $this->logger = $logger;
        $this->parameterBag = $parameterBag;
    }

    protected function getParameter(string $name)
    {
        return $this->parameterBag->get($name);
    }

}
