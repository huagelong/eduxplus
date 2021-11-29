<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 20:41
 */

namespace App\Bundle\AppBundle\Lib\Base;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BaseService extends AbstractFOSRestController
{
    use DBTrait;

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
