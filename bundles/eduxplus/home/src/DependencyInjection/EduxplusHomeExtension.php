<?php
namespace Eduxplus\HomeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * SecurityHeadersExtension
 */
class EduxplusHomeExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }


    public function prepend(ContainerBuilder $container)
    {
        //twig
        $namespace = $container->getExtension("twig")->getAlias();
        $container->prependExtensionConfig($namespace, [
                "paths"=>[
                    __DIR__."/../Resources/templates/default"=>"EduxplusHomeBundle"
                ]
        ]);

        //doctrine
        $namespace = $container->getExtension("doctrine")->getAlias();
        $container->prependExtensionConfig($namespace, [
            "orm" => [
                "mappings" => [
                    'EduxplusHomeBundle' => [
                        'type' => 'annotation',
                        'dir' => 'Entity',
                        'is_bundle' => true,
                        'prefix' => 'Eduxplus\HomeBundle\Entity',
                        'alias' => 'EduxplusHome',
                        ]
                    ]
                ]
        ]);

    }

    
    public function getAlias()
    {
        return 'eduxplus_home';
    }
}
