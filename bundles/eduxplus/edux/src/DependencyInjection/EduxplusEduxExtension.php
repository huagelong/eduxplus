<?php
namespace Eduxplus\EduxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * SecurityHeadersExtension
 */
class EduxplusEduxExtension extends Extension implements PrependExtensionInterface
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
                    __DIR__."/../Resources/templates/default"=>"EduxBundle"
                ]
        ]);

        //doctrine
        $namespace = $container->getExtension("doctrine")->getAlias();
        $container->prependExtensionConfig($namespace, [
            "orm" => [
                "mappings" => [
                    'EduxplusEduxBundle' => [
                        'type' => 'annotation',
                        'dir' => 'Entity',
                        'is_bundle' => true,
                        'prefix' => 'Eduxplus\EduxBundle\Entity',
                        'alias' => 'Edux',
                        ]
                    ]
                ]
        ]);

    }

    
    public function getAlias()
    {
        return 'eduxplus_edux';
    }
}
