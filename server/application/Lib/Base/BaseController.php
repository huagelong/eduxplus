<?php
/**
 * User: Peter Wang
 * Date: 17/2/4
 * Time: 下午6:06
 */

namespace Lib\Base;


use Trensy\Controller;
use Trensy\Shortcut;

class BaseController extends Controller
{

    const RESPONSE_STATUS_CODE_ERROR = "terror";
    const RESPONSE_STATUS_CODE_INFO = "tinfo";
    const RESPONSE_STATUS_CODE_SUCCESS = "tsuccess";
    const RESPONSE_STATUS_CODE_WARNING = "twarning";


    public function getApiRequest($key='', $default=null)
    {
        $content = $this->getRequest()->getContent();
        if(!$content) return $default;
        $jsonArray = json_decode($content, true);
        if(!$key) return $jsonArray;
        return isset($jsonArray[$key])?$jsonArray[$key]:$default;
    }

    /**
     * 成功
     * @param string $errorMsg
     * @param string $url
     */
    public function responseSuccess($errorMsg='', $url=''){
        $errorMsgTmp = [];
        $errorMsgTmp['msgType'] = self::RESPONSE_STATUS_CODE_SUCCESS;
        $errorMsgTmp['msg'] = $errorMsg;
        $url = $url?$url:((object) null);
        return $this->response($url, self::RESPONSE_SUCCESS_CODE, $errorMsgTmp);
    }

    /**
     * 跳转
     *
     * @param $url
     */
    public function responseRedirect($url)
    {
        $errorMsgTmp = [];
        $errorMsgTmp['msgType'] = self::RESPONSE_STATUS_CODE_SUCCESS;
        $errorMsgTmp['msg'] = '';
        return $this->response($url, self::RESPONSE_SUCCESS_CODE, $errorMsgTmp);
    }

    /**
     * 错误
     * @param string $errorMsg
     * @param string $url
     */
    public function responseError($errorMsg='', $url=''){
        $errorMsgTmp = [];
        $errorMsgTmp['msgType'] = self::RESPONSE_STATUS_CODE_ERROR;
        $errorMsgTmp['msg'] = $errorMsg;
        $url = $url?$url:((object) null);
        return $this->response($url, self::RESPONSE_NORMAL_ERROR_CODE, $errorMsgTmp);
    }

    /**
     * 警告
     * @param string $errorMsg
     * @param string $url
     */
    public function responseWarn($errorMsg='', $url=''){
        $errorMsgTmp = [];
        $errorMsgTmp['msgType'] = self::RESPONSE_STATUS_CODE_WARNING;
        $errorMsgTmp['msg'] = $errorMsg;
        $url = $url?$url:((object) null);
        return $this->response($url, self::RESPONSE_NORMAL_ERROR_CODE, $errorMsgTmp);
    }

    /**
     * 信息
     * @param string $errorMsg
     * @param string $url
     */
    public function responseInfo($errorMsg='', $url=''){
        $errorMsgTmp = [];
        $errorMsgTmp['msgType'] = self::RESPONSE_STATUS_CODE_SUCCESS;
        $errorMsgTmp['msg'] = $errorMsg;
        $url = $url?$url:((object) null);
        return $this->response($url, self::RESPONSE_NORMAL_ERROR_CODE, $errorMsgTmp);
    }

    public function responseJson($result)
    {
        $jsonStr = json_encode($result);
        $this->getResponse()->headerStr("Content-Type: application/json");
        return $this->getResponse()->end($jsonStr);
    }
}