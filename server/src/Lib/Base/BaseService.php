<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 20:41
 */

namespace App\Lib\Base;

use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class BaseService extends AbstractFOSRestController
{
    /**
     * @var LoggerInterface
     */
    protected $logger;


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * 添加
     *
     * @param $model
     * @param null $name
     * @return mixed
     */
    public function insert($model, $name=null){
        $entityManage = $this->getDoctrine()->getManager($name);
        $entityManage->persist($model);
        $entityManage->flush();
        return $model->getId();
    }


    /**
     * 更新或者保存
     *
     * @param $model
     * @param null $name
     * @return mixed
     */
    public function update($model, $name=null){
        $entityManage = $this->getDoctrine()->getManager($name);
        $entityManage->flush();
        return $model->getId();
    }

    /**
     * 删除
     *
     * @param $model
     * @param null $name
     * @return bool
     */
    public function delete($model, $name=null){
        $entityManage = $this->getDoctrine()->getManager($name);
        $entityManage->remove($model);
        $entityManage->flush();
        return true;
    }

    public function fetchAllByDql($dql, $params=[]){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        if($params) $query= $query->setParameters($params);
        $rs = $query->getResult();
        return $rs;
    }


    public function fetchOneByDql($dql, $params=[]){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        if($params) $query= $query->setParameters($params);
        $rs = $query->setMaxResults(1)->getOneOrNullResult();
        return $rs;
    }

}
