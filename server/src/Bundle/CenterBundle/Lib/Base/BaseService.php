<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 20:41
 */

namespace App\Bundle\CenterBundle\Lib\Base;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseService extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    protected $logger;


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function save($model, $name=null){
        $entityManage = $this->getDoctrine()->getManager($name);
        $entityManage->persist($model);
        $entityManage->flush();
        return $model->getId();
    }

}
