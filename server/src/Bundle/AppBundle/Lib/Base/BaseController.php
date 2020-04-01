<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace App\Bundle\AppBundle\Lib\Base;


class BaseController extends BaseService
{

    public function responseSuccess($msg='', $url=''){
        $data = [];
        if($msg) $data['_message']= $msg;
        if($url) $data['_url']= $url;
        return $data;
    }

    public function responseRedirect($url){
        $data = [];
        if($url) $data['_url']= $url;
        return $data;
    }

    public function responseError($msg=''){
        $data = [];
        if($msg) $data['message']= $msg;
        $data['code'] = 400;
        return $data;
    }
}
