<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace App\Bundle\CenterBundle\Lib\Base;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractFOSRestController
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

   public function __construct(LoggerInterface $logger)
   {
       $this->logger = $logger;
   }
}
