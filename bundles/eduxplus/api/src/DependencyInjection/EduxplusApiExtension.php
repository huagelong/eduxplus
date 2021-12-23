<?php
namespace Eduxplus\ApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * SecurityHeadersExtension
 */
class EduxplusApiExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }


    public function prepend(ContainerBuilder $container)
    {
        //security
        $namespace = $container->getExtension("security")->getAlias();
        $container->prependExtensionConfig($namespace, [
            "firewalls"=>[
                "api"=>[
                    "pattern"=>"^/api",
                    "stateless"=>true,
                    "anonymous"=>"lazy",
                    "provider"=>"app_user_provider",
                    "guard"=>[
                        "authenticators"=>[
                            "Eduxplus\ApiBundle\Security\TokenAuthenticator",
                            "Eduxplus\ApiBundle\Security\MobileAuthenticator"
                        ],
                        "entry_point"=>"Eduxplus\ApiBundle\Security\TokenAuthenticator"
                    ]
                ]
            ]
        ]);
    }
}
