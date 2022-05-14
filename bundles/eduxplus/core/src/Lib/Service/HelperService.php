<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/17 11:24
 */

namespace Eduxplus\CoreBundle\Lib\Service;


use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class HelperService
{

    function isNotJson($str){
        return is_null(json_decode($str));
    }

    public function getUuid(){
        $uuid = Uuid::uuid4();
        return $uuid->toString();
    }

    /**
     *
     * 检查手机号码格式
     *
     * @param $tel
     */
    public function isMobile($tel)
    {
        $validate = new ValidateService();
       return $validate->mobileValidate($tel);
    }

    public function formatMobile($tel)
    {
        if($this->isMobile($tel)){
            return substr_replace($tel, '****', 3, 4);
        }else{
            return $tel;
        }
    }

    public function isMobileClient(Request $request)
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        $server = $request->server->all();
        if (isset($server['HTTP_X_WAP_PROFILE'])) {
            return true;
        }

        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($server['HTTP_VIA'])) {
            //找不到为flase,否则为true
            return stristr($server['HTTP_VIA'], "wap") ? true : false;
        }

        //判断手机发送的客户端标志,兼容性有待提高
        if (isset($server['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp',
                'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu',
                'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi',
                'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
            );

            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($server['HTTP_USER_AGENT']))) {
                return true;
            }
        }

        //协议法，因为有可能不准确，放到最后判断
        if (isset($server['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($server['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($server['HTTP_ACCEPT'], 'text/html') === false || (strpos($server['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($server['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }

        return false;
    }

    public function qtime($time){
        $limit = time() - $time;
        if($limit<60)
            $time="{$limit}秒前";
        if($limit>=60 && $limit<3600){
            $i = floor($limit/60);
            $_i = $limit%60;
            $s = $_i;
            $time="{$i}分前";
        }
        if($limit>=3600 && $limit<3600*24){
            $h = floor($limit/3600);
            $_h = $limit%3600;
            $i = ceil($_h/60);
            $time="{$h}小时{$i}分前";
        }
        if($limit>=(3600*24) && $limit<(3600*24*30)){
            $d = floor($limit/(3600*24));
            $time= "{$d}天前";
        }
        if($limit>=(3600*24*30)){
            $time=gmdate('Y-m-d H:i', $time);
        }
        return $time;
    }


    public final function baseCurlGet($url, $method, $body="")
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


}
