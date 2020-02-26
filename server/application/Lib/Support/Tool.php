<?php
/**
 * Created by PhpStorm.
 * User: wangkh
 * Date: 2018/9/27
 * Time: 17:07
 */

namespace Lib\Support;


use Trensy\Context;

class Tool
{
    public static  function getClientIp()
    {
        $request =  Context::request();
        if(!$request) return "127.0.0.1";
        if ( $request->server->get(strtolower("HTTP_X_FORWARDED_FOR"))) { //#透过代理服务器取得客户端的真实 IP 地址
            $ip =  $request->server->get(strtolower("HTTP_X_FORWARDED_FOR"));
        } elseif ($request->server->get(strtolower("HTTP_CLIENT_IP"))) { //#客户端IP
            $ip = $request->server->get(strtolower("HTTP_CLIENT_IP"));
        } elseif ($request->server->get(strtolower("REMOTE_ADDR"))) { //#正在浏览当前页面用户的 IP 地址
            $ip =$request->server->get(strtolower("REMOTE_ADDR"));
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {  //#透过代理服务器取得客户端的真实 IP 地址
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {  //#客户端IP
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("REMOTE_ADDR")) {  //#正在浏览当前页面用户的 IP 地址
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "127.0.0.1";
        }
        return $ip;
    }


}