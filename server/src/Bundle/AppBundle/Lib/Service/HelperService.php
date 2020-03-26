<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/17 11:24
 */

namespace App\Bundle\AppBundle\Lib\Service;


use App\Bundle\AppBundle\Lib\Base\BaseService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class HelperService extends BaseService
{

    public function getUuid(){
        $uuid = Uuid::uuid4();
        return $uuid->toString();
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

}
