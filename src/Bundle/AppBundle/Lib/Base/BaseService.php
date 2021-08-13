<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 20:41
 */

namespace App\Bundle\AppBundle\Lib\Base;

use App\Entity\BaseUser;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BaseService extends AbstractFOSRestController
{

    protected static $originalEventListeners=[];

    public function error()
    {
        return new Error();
    }

    public function setFlash(string $type, string $message)
    {
        $this->addFlash($type, $message);
    }

    public function genUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->generateUrl($route, $parameters, $referenceType);
    }

    public function session()
    {
        return $this->request()->getSession();
    }


    public function dump($str)
    {
        \Doctrine\Common\Util\Debug::dump($str);
    }

    /**
     * @return Request
     */
    public function request()
    {
        $requestStack = $this->get("request_stack");
        return $requestStack->getCurrentRequest();
    }

    public function getConfig($str)
    {
        return $this->getParameter($str);
    }



    /**
     * 获取项目路径
     *
     * @return void
     */
    public function getBasePath()
    {
        return $this->getParameter("kernel.project_dir");
    }


    /**
     * 添加
     *
     * @param $model
     * @param null $name
     * @return mixed
     */
    public function insert($model, $name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $em->persist($model);
        $em->flush();
        $em->clear($name);
        return $model->getId();
    }


    public function save($model, $name = null)
    {
        $id = $model->getId();
        if ($id) return $this->update($model, $name);
        return $this->insert($model, $name);
    }

    /**
     * 更新或者保存
     *
     * @param $model
     * @param null $name
     * @return mixed
     */
    public function update($model, $name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $em->persist($model);
        $em->flush();
        $em->clear($name);
        return $model->getId();
    }

    /**
     * 软删除
     *
     * @param $model
     * @param null $name
     * @return bool
     */
    public function delete($models, $name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        if (is_array($models)) {
            foreach ($models as $model) {
                $em->remove($model);
            }
        } else {
            $em->remove($models);
        }
        $em->flush();
        $em->clear($name);
        return true;
    }

    /**
     * dql删除,更新等
     *
     * @param $dql
     * @param null $name
     */
    public function execute($dql, $params = [], $name = null)
    {
        //execute
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);
        $rs = $query->execute();
        $em->clear($name);
        return $rs;
    }

    /**
     * 硬处理
     * @param $dql
     * @param array $params
     * @param null $name
     * @return mixed
     */
    public function hardExecute($dql, $params = [], $name = null)
    {
        //execute
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->disableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);
        $rs = $query->execute();
        $em->clear($name);
        return $rs;
    }

    /**
     * 硬删除
     *
     * @param $models
     * @param null $name
     * @return bool
     */
    public function hardDelete($models, $name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->disableSoftDeleteable($em);
        if (is_array($models)) {
            foreach ($models as $model) {
                $em->remove($model);
            }
        } else {
            $em->remove($models);
        }
        $em->flush();
        $em->clear($name);
        return true;
    }

    public function fetchField($field, $dql, $params = [])
    {
        $result = $this->fetchOne($dql, $params);
        $rs = isset($result[$field]) ? $result[$field] : "";
        return $rs;
    }

    public function fetchFields($field, $dql, $params = [])
    {
        $result = $this->fetchAll($dql, $params);
        $rs = $result ? array_column($result, $field) : [];
        return $rs;
    }

    public function fetchAll($dql, $params = [], $getObject = 0, $limit = null, $offset = null, $name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);

        if ($limit !== null) {
            $query = $query->setMaxResults($limit);
        }

        if ($offset !== null) {
            $query = $query->setFirstResult($offset);
        }

        if (!$getObject) {
            $rs = $query->getArrayResult();
        } else {
            $rs = $query->getResult();
        }
        //        dump($query->getSql());
        return $rs ? $rs : [];
    }


    public function fetchAllHard($dql, $params = [], $getObject = 0, $limit = null, $offset = null, $name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->disableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);

        if ($limit !== null) {
            $query = $query->setMaxResults($limit);
        }

        if ($offset !== null) {
            $query = $query->setFirstResult($offset);
        }

        if (!$getObject) {
            $rs = $query->getArrayResult();
        } else {
            $rs = $query->getResult();
        }
        //        dump($query->getSql());

        return $rs ? $rs : [];
    }

    public function fetchOne($dql, $params = [], $getObject = 0, $name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->enableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);
        $resultType = !$getObject ? 2 : null;
        $rs = $query->setMaxResults(1)->getOneOrNullResult($resultType);
        return $rs ? $rs : [];
    }

    public function fetchOneHard($dql, $params = [], $getObject = 0, $name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em = $this->disableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        if ($params) $query = $query->setParameters($params);
        $resultType = !$getObject ? 2 : null;
        $rs = $query->setMaxResults(1)->getOneOrNullResult($resultType);
        return $rs ? $rs : [];
    }


    /**
     * dbal conn
     *
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function conn($name = null)
    {
        $conn = $this->getDoctrine()->getManager($name)->getConnection();
        return  $conn;
    }

    /**
     * 原生sql，多个值
     * @param $sql  eg "SELECT * FROM mall_msg WHERE uid =?" or "SELECT * FROM mall_msg WHERE uid =:uid"
     * @param array $params eg [1] or [":uid"=>uid]
     * @param array $types
     * @param null $name
     * @return mixed
     */
    public function fetchAllBySql($sql, array $params = [],$name = null){
        $conn = $this->conn($name);
        $result = $conn->fetchAll($sql, $params);
        return $result;
    }

    /**
     * 原生sql，第一个值
     * @param $sql
     * @param array $params
     * @param array $types
     * @param null $name
     * @return mixed
     */
    public function fetchAssocBySql($sql, array $params = [],$name = null){
        $conn = $this->conn($name);
        $result = $conn->fetchAssoc($sql, $params);
        return $result;
    }

    /**
     * 原生sql，第一行，单个字段值
     * @param $sql  eg "SELECT * FROM App:MallMsg WHERE uid =?";
     * @param array $params eg ["App:MallMsg"]
     * @param array $types
     * @param null $name
     * @return mixed
     */
    public function fetchColumnBySql($sql, array $params = [], $types = [],$name = null){
        $conn = $this->conn($name);
        $result = $conn->fetchcolumn($sql, $params);
        return $result;
    }


    /**
     * @param $sql
     * @param array $params
     * @param null $name
     * @return string|string[]
     */
    public function formatTableClass($sql, $params=[], $name=null){
        if(!$params) return $sql;
        $em = $this->getDoctrine()->getManager($name);
        foreach ($params as $v){
            $tableName = $em->getClassMetadata($v)->getTableName();
            if($tableName) $sql = str_replace($v, $tableName, $sql);
        }
        $em->clear($name);
        return $sql;
    }

    /**
     * @param $dbTableClassName eg 'App:BaseUser'
     * @param null $name
     */
    public function getTableName($dbTableClassName, $name=null){
        $em = $this->getDoctrine()->getManager($name);
        $tableName = $em->getClassMetadata($dbTableClassName)->getTableName();
        $em->clear($name);
        return $tableName;
    }

    public function beginTransaction($name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em->getConnection()->beginTransaction();
        return $em;
    }

    public function commit($name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em->getConnection()->commit();
        return $em;
    }

    public function rollback($name = null)
    {
        $em = $this->getDoctrine()->getManager($name);
        $em->getConnection()->rollback();
        return $em;
    }


    protected function disableSoftDeleteable($em)
    {
        foreach ($em->getEventManager()->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Gedmo\SoftDeleteable\SoftDeleteableListener) {
                    self::$originalEventListeners[$eventName] = $listener;
                    $em->getEventManager()->removeEventListener($eventName, $listener);
                }
            }
        }
        if ($em->getFilters()->isEnabled("softdeleteable")) {
            $em->getFilters()->disable('softdeleteable');
        }
        return $em;
    }

    protected function enableSoftDeleteable($em)
    {
        foreach (self::$originalEventListeners as $eventName => $listener) {
            $em->getEventManager()->addEventListener($eventName, $listener);
        }

        if (!$em->getFilters()->isEnabled("softdeleteable")) {
            $em->getFilters()->enable('softdeleteable');
        }
        return $em;
    }

    public function getOption($k, $isJson = 0, $index = null, $default = null)
    {
        $sql = "SELECT a.optionValue FROM App:BaseOption a WHERE a.optionKey =:optionKey";
        $rs = $this->fetchField("optionValue", $sql, ['optionKey' => $k]);

        if ($rs) {
            if ($isJson) {
                $arr =  json_decode($rs, 1);
                if($index === null){
                    return $arr;
                }
                return isset($arr[$index]) ? $arr[$index] : "";
            }
            return $rs;
        } else {
            return $default;
        }
    }


    public function toArray($model)
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($model, 'json');
        return json_decode($json, true);
    }

    public function getPro($obj, $name)
    {

        $method = "get" . ucfirst($name);

        if (method_exists($obj, $method)) {
            $rs = call_user_func([$obj, $method]);
            return $rs;
        } else {
            if (is_array($obj)) {
                $rs = isset($obj[$name]) ? $obj[$name] : "";
                return $rs;
            }
        }
    }

    public function getEnv()
    {
        $env = $_SERVER['APP_ENV'];
        return $env;
    }

    /**
     * @param $token
     * @param $clientId
     * @return BaseUser
     */
    public function getUserByToken($token, $clientId)
    {
        if ($clientId == 'ios' || $clientId == 'android') {
            $sql = "SELECT a FROM App:BaseUser a WHERE a.appToken=:appToken";
            return $this->fetchOne($sql, ["appToken" => $token], 1);
        } elseif ($clientId == 'html') {
            $sql = "SELECT a FROM App:BaseUser a WHERE a.htmlToken=:htmlToken";
            return $this->fetchOne($sql, ["htmlToken" => $token], 1);
        } elseif ($clientId == 'wxmini') {
            $sql = "SELECT a FROM App:BaseUser a WHERE a.wxminiToken=:wxminiToken";
            return $this->fetchOne($sql, ["wxminiToken" => $token], 1);
        }
    }


    public function baseCurlGet($url, $method, $body="")
    {
        //        debug(func_get_args());
        $method = strtoupper($method);
        $ch = curl_init();

        if ($method == 'POST' || $method == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if($body) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (substr($url, 0, 5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $rtn = curl_exec($ch);

        if ($rtn === false) {
            // 大多由设置等原因引起，一般无法保障后续逻辑正常执行，
            // 所以这里触发的是E_USER_ERROR，会终止脚本执行，无法被try...catch捕获，需要用户排查环境、网络等故障
            trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
        }
        curl_close($ch);

        return $rtn;
    }

    public function jsonGet($json, $key = 0)
    {
        if (!$json) return "";
        $arr = json_decode($json, true);
        return isset($arr[$key]) ? $arr[$key] : "";
    }
}
