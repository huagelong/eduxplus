<?php

namespace App\Bundle\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GetPropExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_prop', [$this, 'doSomething']),
        ];
    }

    public function doSomething($obj, $name = "", $type = null, $options = null)
    {
        if(!$obj) return $obj;
        if ($type == null) {
            if (is_callable($obj)) {
                return call_user_func($obj, $name);
            }
        }

        if ($name == "" && $type == null && $options == null) return $obj;


        $method = "get" . ucfirst($name);
        if ($type == 'datetime') {
            if (is_array($obj)) {
                if (isset($obj[$name])) {
                    return date('Y-m-d H:i:s', $obj[$name]['timestamp']);
                } else {
                    return "-";
                }
            }else {
                if (method_exists($obj, $method)) {
                    $classObj = call_user_func([$obj, $method]);
                    $datetime = date('Y-m-d H:i:s', $classObj->getTimestamp());
                    return $datetime;
                }
            }
        } elseif ($type == 'textarea') {
            if (is_array($obj)) {
                $value = isset($obj[$name]) ? $obj[$name] : "";
                return "<div class='text-wrap'>" . $value . "</div>";
            }else {
                if (method_exists($obj, $method)) {
                    return "<div class='text-wrap'>" . call_user_func([$obj, $method]) . "</div>";
                }
            }
        } elseif ($type == 'image') {
            if (is_array($obj)) {
                $rs = isset($obj[$name]) ? $obj[$name] : "";
                if (!$rs) {
                    return "";
                }
            } else {
                $rs = call_user_func([$obj, $method]);
            }
            if (is_null(json_decode($rs))) {
                return $rs;
            } else {
                $arr = json_decode($rs, true);
                return current($arr);
            }
        } else {
            if (is_array($obj)) {
                $rs = isset($obj[$name]) ? $obj[$name] : "";
                if ($rs === "") {
                    if($type == 'boole'){
                        return "";
                    }
                    return "-";
                }
                if($options){
                    return isset($options[$rs])?$options[$rs]:$rs;
                }else{
                    return $rs;
                }
//                    return $options ? $options[$rs] : $rs;
            }else {
                if (method_exists($obj, $method)) {
                    $rs = call_user_func([$obj, $method]);
                    return $options ? $options[$rs] : $rs;
                }
            }
        }
    }
}
