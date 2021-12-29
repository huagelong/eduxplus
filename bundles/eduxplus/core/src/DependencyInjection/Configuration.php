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
            ->arrayNode('path_patterns')->scalarPrototype()->end()->end()
            ->end()
            ->end()
        ;
        return $builder;
    }
}
