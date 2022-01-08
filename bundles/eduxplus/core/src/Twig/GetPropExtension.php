<?php

namespace Eduxplus\CoreBundle\Twig;

use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GetPropExtension extends AbstractExtension
{
    protected $baseService;
    public function __construct(BaseService $baseService){
        $this->baseService = $baseService;
    }

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
        $obj = $this->baseService->toArray($obj);
        $value = isset($obj[$name]) ? $obj[$name] : "";
        if ($type == 'datetime') {
            if($value) return $value;
            return "-";
        } elseif ($type == 'textarea') {
            return "<div class='text-wrap'>" . $value . "</div>";
        } elseif ($type == 'image') {
            if (is_null(json_decode($value))) {
                return $value;
            } else {
                $arr = json_decode($value, true);
                return current($arr);
            }
        } else {

            if($value) return $value;
            if($type == 'boole') return "";
            return "-";
        }
    }
}
