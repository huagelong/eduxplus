<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace Eduxplus\CoreBundle\Lib\Base;


use Eduxplus\CoreBundle\Lib\Service\JsonResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class BaseController extends AbstractController
{

    public function genUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->generateUrl($route, $parameters, $referenceType);
    }

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

    public function error()
    {
        return new Error();
    }

    abstract public function getUid();
    abstract public function getUserInfo();

}
