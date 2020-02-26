<?php
/**
 *  函数
 * Trensy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      trensy, Inc.
 * @package         trensy/framework
 * @version         3.0.0
 */

if(!function_exists("env")){
    function env($str, $default=null)
    {
        $getEnv = getenv($str);
        return $getEnv?$getEnv:$default;
    }
}

if(!function_exists('debug')){

    function debug($str, $isReturn=false,$line=0)
    {
        if(!$isReturn){
            $data = debug_backtrace(2, 7);
            $result = isset($data[$line])?$data[$line]:$data[0];
            $func = isset($result['function'])?$result['function']:null;
            $file = isset($result['file'])?$result['file']:null;
            $strTmp = "";
            if($file){
                if($func){
                    $strTmp = "{$result['function']}(): {$result['file']} . (line:{$result['line']})";
                }
                else{
                    $strTmp = " {$result['file']} . (line:{$result['line']})";
                }
            }

            if($strTmp) \Trensy\Log::show($strTmp);

            return \Trensy\Log::debug($str);
        }
        ob_start();
        \Trensy\Log::debug($str);
        $msg = ob_get_clean();
        return $msg;
    }

}