<?php

namespace App\Bundle\AdminBundle\Twig;

use App\Bundle\AdminBundle\Service\RoleService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class IsAuthorizedExtension extends AbstractExtension
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_authorized', [$this, 'doSomething']),
        ];
    }

    public function doSomething($routeName)
    {
        $uid = $this->roleService->getUid();
        return $this->roleService->isAuthorized($uid, $routeName);
    }
}
