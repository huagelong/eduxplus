<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class InArrayExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('in_array', [$this, 'doSomething']),
        ];
    }

    public function doSomething($value, $arr)
    {
        return in_array($value, $arr);
    }
}
