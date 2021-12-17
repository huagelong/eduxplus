<?php

namespace Eduxplus\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('eduxplus_core');
        $builder->getRootNode()
            ->children()
            ->end()
            ->end()
        ;
        return $builder;
    }
}
