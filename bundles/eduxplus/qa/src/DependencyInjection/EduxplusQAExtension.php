<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/28 19:51
 */

namespace Eduxplus\QABundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EduxplusQAExtension extends Extension implements PrependExtensionInterface
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->import('services.yaml');

    }

    public function prepend(ContainerBuilder $container)
    {
//twig
        $namespace = $container->getExtension("twig")->getAlias();
        $container->prependExtensionConfig($namespace, [
            "paths" => [
                __DIR__ . "/../Resources/templates" => "QABundle",
                __DIR__ . "/../Resources/templates/admin" => "QABundleAdmin",
            ]
        ]);

        //orm config
        $container->loadFromExtension("doctrine", [
            "orm"=>[
                "mappings"=>[
                    'QA' => [
                        'type'      => 'annotation',
                        'dir'       => '%kernel.project_dir%/src/Bundle/QABundle/Entity',
                        'is_bundle' => false,
                        'prefix'    => 'Eduxplus\QABundle\Entity',
                        'alias'     => 'QA',
                    ],
                ]
            ]
        ]);

    }
}
