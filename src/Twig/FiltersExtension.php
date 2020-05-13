<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FiltersExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('substr', [$this, 'doSomething'])
        ];
    }

    public function doSomething($string , $start, $length=null)
    {
        return substr($string , $start, $length);
    }
}
