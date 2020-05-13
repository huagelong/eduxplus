<?php

namespace App\Twig;

use App\Bundle\AppBundle\Lib\Base\BaseService;
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
            new TwigFunction('option', [$this, 'doOption']),
            new TwigFunction('array_get', [$this, 'doArrGet']),
            new TwigFunction('in_array', [$this, 'doInArray']),
        ];
    }

    public function doInArray($value, $arr)
    {
        return in_array($value, $arr);
    }

    public function doArrGet($arr, $name)
    {
        return isset($arr[$name])?$arr[$name]:null;
    }

    public function doOption($key, $isJson=0,$index=0,$default="")
    {
        return $this->service->getOption($key, $isJson, $index, $default);
    }
}
