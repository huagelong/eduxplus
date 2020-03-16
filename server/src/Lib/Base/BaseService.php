<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 20:41
 */

namespace App\Lib\Base;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Connection;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class BaseService extends AbstractFOSRestController
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

//    /**
//     * @var \Doctrine\DBAL\Query\QueryBuilder
//     */
//    protected $dbQuery;


    public function __construct(LoggerInterface $logger, Connection $conn)
    {
        $this->logger = $logger;
//        $this->dbQuery = $conn->createQueryBuilder();
    }

    /**
     * 获取执行的sql
     * @return string
     */
    public function getSQL(){
        return $this->dbQuery->getSQL();
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
