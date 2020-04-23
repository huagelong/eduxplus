<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/22 17:07
 */

include_once "bootstrap.php";

use \Symfony\Component\DependencyInjection\Container;

$container = new Container();

$service = $container->get(\App\Bundle\AppBundle\Lib\Service\Vod\BokeccService::class);
var_dump($service->categoryCreate("测试分类"));
