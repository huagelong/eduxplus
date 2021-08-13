<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace App\Bundle\AppBundle\Lib\Base;


use App\Bundle\AppBundle\Lib\Service\JsonResponseService;

abstract class BaseController extends BaseService
{

    public function responseSuccess($data, $msg=''){
        return JsonResponseService::genData($data, 200, $msg);
    }

    public function responseMsgRedirect($msg='', $url=''){
        return JsonResponseService::genData([], 200, $msg, $url);
    }

    public function responseRedirect($url){
        return JsonResponseService::genData([], 200, '', $url);
    }

    public function responseError($msg='', $code = 400){
        return JsonResponseService::genData([], $code, $msg);
    }

    abstract public function getUid();

    public function getUserInfo(){
        $uid = $this->getUid();
        $sql = "SELECT a FROM App:BaseUser a WHERE a.id = :id";
        $model = $this->fetchOne($sql, ["id"=>$uid]);
        return $model;
    }

}
