<?php
/**
 * User: Peter Wang
 * Date: 17/3/14
 * Time: 下午4:28
 */

namespace Lib\Support;


class Valid
{


    public static function isMobile($mobile)
    {
       return preg_match("/^1\d{10}$/", $mobile);
    }

    public static function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isIp($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /**
     *  字母,数字,下划线 6-12字符
     * @param $account
     * @return mixed
     */
    public static function isAccount($account)
    {
        return preg_match("/^[a-zA-Z0-9_]{6,12}$/", $account);
    }

    /**
     *  呢称认证
     *  字母,数字,中文,下划线, 2-12字符
     *
     * @param $displayName
     * @return bool
     */
    public static function isDisplayName($displayName)
    {
        $check = preg_match('/^[\x{4e00}-\x{9fa5}\w\_]+$/',$displayName);
        if($check){
            $len = mb_strlen($check, "utf8");
            if($len<2) return false;
            if($len>12) return false;
            return true;
        }
        return false;
    }

}