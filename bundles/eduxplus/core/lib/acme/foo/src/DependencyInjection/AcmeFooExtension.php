<?php
namespace Acme\FooBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * SecurityHeadersExtension
 */
class AcmeFooExtension extends Extension implements PrependExtensionInterface
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
                    __DIR__."/../Resources/templates/default"=>"AcmeFooBundle"
                ]
        ]);

        //doctrine
        $namespace = $container->getExtension("doctrine")->getAlias();
        $container->prependExtensionConfig($namespace, [
            "orm" => [
                "mappings" => [
                    'AcmeFooBundle' => [
                        'type' => 'annotation',
                        'dir' => 'Entity',
                        'is_bundle' => true,
                        'prefix' => 'Acme\FooBundle\Entity',
                        'alias' => 'AcmeFoo',
                        ]
                    ]
                ]
        ]);

    }

    
    public function getAlias()
    {
        return 'acme_foo';
    }
}
