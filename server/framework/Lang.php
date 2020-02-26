<?php
/**
 * i18n 支持
 *
 */
namespace Trensy;

use Trensy\Config;

class Lang
{
    protected static $lang = "zh_cn";
    protected static $config=[];

    public static function setLang($lang)
    {
        self::$lang = $lang;
    }

    public static function get($string, $args=null){
        if(is_array($string)) return $string;
        $key = "lang.".self::$lang.".".$string;
        $realStr = Config::get($key);
        if(!$realStr || is_array($realStr)) return $string;

        $args = is_array($args)?$args:[$args];
        return vsprintf($realStr, $args);
    }
}

