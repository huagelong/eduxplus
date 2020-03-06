<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 20:41
 */

namespace App\ExpCenterBundle\Lib\Base;

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

    /**
     * @var ContainerInterface
     */
    protected $container;


    public function __construct(LoggerInterface $logger, ContainerInterface $parameterBag, ContainerInterface $container )
    {
        $this->logger = $logger;
        $this->parameterBag = $parameterBag;
        $this->container = $container;
    }

    protected function getParameter(string $name)
    {
        return $this->parameterBag->get($name);
    }

    protected function get(string $name){
        return $this->container->get($name);
    }
}
