<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/12 14:56
 */

namespace Eduxplus\CoreBundle\Lib\Service;


use Symfony\Component\HttpFoundation\JsonResponse;

class JsonResponseService
{


    public static function genData($rsData=[], $code=200, $msg='', $url = ''){
        $data = [];
        $data["code"] = $code;
        $data['_message']= $msg;
        $data['_url']= $url;
        $data['_data']= $rsData;
        $jsonResponse = new JsonResponse(self::format($code, $data));
        return $jsonResponse;
    }

    public static function format($statusCode, $content){
        $message = isset($content['_message'])?$content['_message']:"";
        if(isset($content['_message'])) unset($content['_message']);
        $url = isset($content['_url'])?$content['_url']:"";
        if(isset($content['_url'])) unset($content['_url']);
        $data = isset($content['_data'])?$content['_data']:$content;
        if(isset($content['_data'])) unset($content['_data']);

        if(empty($data)) $data = (object) null;

        if(!isset($content['code'])){
            $responseData['code'] = $statusCode;
            $responseData['data'] = $data;
        }else{
            $responseData = $content;
            if($content['code'] != 200){
                $responseData['data'] = (object) null;
            }
        }
        if(!isset($responseData['data'])) $responseData['data'] = $data;
        $responseData['message']=$message;
        if($url) $responseData['_url']=$url;
        return $responseData;
    }
}
