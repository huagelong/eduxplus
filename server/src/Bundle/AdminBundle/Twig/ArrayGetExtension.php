<?php

namespace App\Bundle\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ArrayGetExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('array_get', [$this, 'doSomething']),
        ];
    }

    public function doSomething($arr, $name)
    {
        return isset($arr[$name])?$arr[$name]:null;
    }
}
