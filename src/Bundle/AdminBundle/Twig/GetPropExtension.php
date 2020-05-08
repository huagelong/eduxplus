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

    public function doSomething($obj, $name, $type=null, $options=null)
    {
        if($type == null){
            if(is_callable($obj)){
                return call_user_func($obj, $name);
            }
        }

        $method = "get".ucfirst($name);
        if($type == 'datetime'){
            if(method_exists($obj, $method)){
                $classObj = call_user_func([$obj, $method]);
                $datetime = date('Y-m-d H:i:s',$classObj->getTimestamp());
                return $datetime;
            }else{
                if(is_array($obj)){
                    if(isset($obj[$name])){
                        return date('Y-m-d H:i:s', $obj[$name]['timestamp']);
                    }else{
                        return "";
                    }
                }
            }
        }elseif($type == 'textarea'){
            if(method_exists($obj, $method)) {
                return "<div class='font-weight-lighter'>" . call_user_func([$obj, $method]) . "</div>";
            }else{
                if(is_array($obj)){
                    $value = isset($obj[$name])?$obj[$name]:"";
                    return "<div class='font-weight-lighter'>" . $value . "</div>";
                }
            }
        }else{
            if(method_exists($obj, $method)) {
                $rs = call_user_func([$obj, $method]);
                return $options ? $options[$rs] : $rs;
            }else{
                if(is_array($obj)){
                    $rs = isset($obj[$name])?$obj[$name]:"";
                    return $options ? $options[$rs] : $rs;
                }
            }
        }
    }
}
