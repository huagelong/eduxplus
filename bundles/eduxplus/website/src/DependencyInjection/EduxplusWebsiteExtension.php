<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/28 19:51
 */

namespace Eduxplus\WebsiteBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EduxplusWebsiteExtension extends Extension implements PrependExtensionInterface
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
            "paths"=>[
                __DIR__."/../Resources/templates"=>"WebsiteBundle",
            ]
        ]);

        //security
        $namespace = $container->getExtension("security")->getAlias();
        $container->prependExtensionConfig($namespace, [
            "access_control"=>[
                [
                    "path"=>"/my[/]?",
                    "roles"=>"ROLE_USER"
                ]
            ],
            "firewalls"=>[
                "app"=>[
                    "pattern"=>"^/",
                    "anonymous"=>"lazy",
                    "provider"=>"app_user_provider",
                    "guard"=>[
                        "authenticators"=>[
                            "Eduxplus\WebsiteBundle\Security\MobileAuthenticator"
                        ]
                    ],
                    "remember_me"=>[
                        "secret"=>"%kernel.secret%",
                        "lifetime"=>604800,
                        "path"=>"/"
                    ],
                    "logout"=>[
                        "path"=>"app_logout",
                        "success_handler"=>"Eduxplus\WebsiteBundle\Security\LogoutSuccessHandle"
                    ],
                ]
            ]
        ]);
    }
}
