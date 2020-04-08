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
            $classObj = call_user_func([$obj, $method]);
            $datetime = date('Y-m-d H:i:s',$classObj->getTimestamp());
            return $datetime;
        }elseif($type == 'textarea'){
            return "<span class='overflow-auto font-weight-lighter'>".call_user_func([$obj, $method])."</span>";
        }else{
            $rs = call_user_func([$obj, $method]);
            return $options?$options[$rs]:$rs;
        }
    }
}
