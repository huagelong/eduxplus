<?php

namespace Eduxplus\CoreBundle\Twig;

use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FuncExtension extends AbstractExtension
{

    protected $service;

    public function __construct(BaseService $service)
    {
        $this->service = $service;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('date_time', [$this, 'doDateTime']),
            new TwigFunction('option', [$this, 'doOption']),
            new TwigFunction('array_get', [$this, 'doArrGet']),
            new TwigFunction('in_array', [$this, 'doInArray']),
            new TwigFunction('json_get', [$this, 'doJsonGet']),
            new TwigFunction('if_get', [$this, 'doIfGet']),
            new TwigFunction('if_set', [$this, 'doIfSet']),
            new TwigFunction('diff', [$this, 'doDiff']),
            new TwigFunction('inArrayDiff', [$this, 'doInArrayDiff']),
            new TwigFunction('dump', [$this, 'doDump']),
            new TwigFunction('sum', [$this, 'doSum']),
        ];

    }


    public function doDateTime($obj)
    {
        if(is_object($obj)){
            $datetime = date('Y-m-d H:i:s',$obj->getTimestamp());
            return $datetime;
        }else if(is_array($obj)){
            $datetime = date('Y-m-d H:i:s',$obj['timestamp']);
            return $datetime;
        }else{
            $str = $obj+0;
            if($str == $obj) return date('Y-m-d H:i:s',$obj);
            return $obj;
        }
    }

    public function doInArray($value, $arr)
    {
        return in_array($value, $arr);
    }

    public function doArrGet($arr, $name)
    {
        if(!$arr) return $arr;
        if (is_array($arr)) {
            return isset($arr[$name]) ? $arr[$name] : null;
        }else{
            $method = "get" . ucfirst($name);
            if (method_exists($arr, $method)) {
                return call_user_func([$arr, $method]);
            }
        }
        return null;
    }

    public function doOption($key, $isJson=0,$index=0,$default="")
    {
        return $this->service->getOption($key, $isJson, $index, $default);
    }


    public function doIfSet($arr, $key, $default=""){
        return isset($arr[$key]) ?$arr[$key]:$default;
    }


    public function doIfGet($arr, $default=""){
        return (isset($arr) && $arr) ?$arr:$default;
    }

    public function doJsonGet($json, $key=0){
        if(!$json) return "";
        $arr = json_decode($json, true);
        return isset($arr[$key])?$arr[$key]:"";
    }

    public function doDiff($v1, $v2, $tag="checked"){
        if(!$this->doIfGet($v1)) return "";
        return $v1==$v2?$tag:"";
    }

    public function doInArrayDiff($arr, $v2, $tag="checked"){
        if(!$this->doIfGet($arr)) return "";
        if(!is_array($arr)){
            $arr = explode(",", $arr);
        }
        return in_array($v2, $arr)?$tag:"";
    }
    

    public function doDump($value){
        if(!$value) return $value;
        if(is_array($value) || is_object($value)){
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }else{
            return $value;
        }
    }

    public function doSum($one, $two){
        return $one+$two;
    }
}
