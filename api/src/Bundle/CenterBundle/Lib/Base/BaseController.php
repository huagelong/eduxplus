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

class BaseController extends AbstractController
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
