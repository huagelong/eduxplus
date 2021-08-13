<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/29 14:17
 */

namespace App\Bundle\QABundle;


use Symfony\Bundle\TwigBundle\Loader\NativeFilesystemLoader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Pass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $twigFilesystemLoaderDefinition = $container->getDefinition('twig.loader.native_filesystem');
        if ($container->getParameter('kernel.debug')) {
            $twigFilesystemLoaderDefinition->setClass(NativeFilesystemLoader::class);
        }
        $twigFilesystemLoaderDefinition->addMethodCall('addPath', array(__DIR__."/Resources/templates/admin", 'QABundleAdmin'))
            ->addMethodCall('addPath', array(__DIR__."/Resources/templates/default", 'QABundle'))
        ;
    }
}
