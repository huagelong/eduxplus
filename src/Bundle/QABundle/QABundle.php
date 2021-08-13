<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/28 17:16
 */

namespace App\Bundle\QABundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * 题库
 * Class QuestionBundle
 * @package App\Bundle\QABundle
 */
class QABundle extends Bundle
{

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        //twig 处理
        $container->addCompilerPass(new Pass());
        //orm config
        $container->loadFromExtension("doctrine", [
            "orm"=>[
                "mappings"=>[
                    'QA' => [
                        'type'      => 'annotation',
                        'dir'       => '%kernel.project_dir%/src/Bundle/QABundle/Entity',
                        'is_bundle' => false,
                        'prefix'    => 'App\Bundle\QABundle\Entity',
                        'alias'     => 'QA',
                    ],
                ]
            ]
        ]);

    }

}
