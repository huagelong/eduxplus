<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2017/4/13
 * Time: 11:10
 */

namespace Lib\Support;


use Trensy\Shortcut;

class Error
{
    use Shortcut;

    protected static $errorData = [];

    public static function add($msg)
    {
        //请求结束收回资源
        self::responseEnd(function()
        {
            self::$errorData=[];
        });

        self::$errorData[] = $msg;

        return false;
    }

    public static function getAll()
    {
        return self::$errorData;
    }

    public static function getLast()
    {
        return end(self::$errorData);
    }

    public static function has()
    {
        return self::$errorData?true:false;
    }
}