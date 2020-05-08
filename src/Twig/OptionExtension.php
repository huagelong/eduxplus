<?php

namespace App\Twig;

use App\Bundle\AppBundle\Lib\Base\BaseService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class OptionExtension extends AbstractExtension
{

    protected $service;

    public function __construct(BaseService $service)
    {
        $this->service = $service;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('option', [$this, 'doSomething']),
        ];
    }

    public function doSomething($key, $isJson=0,$index=0,$default="")
    {
        return $this->service->getOption($key, $isJson, $index, $default);
    }
}
