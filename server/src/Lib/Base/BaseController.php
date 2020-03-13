<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace App\Lib\Base;

use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;

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
