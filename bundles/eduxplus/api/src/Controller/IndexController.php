<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace Eduxplus\ApiBundle\Controller;

use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;

/**
 * @package Eduxplus\ApiBundle\Controller
 */
class IndexController extends BaseApiController
{
    /**
     * @Rest\Post("/")
     */
    public function index()
    {
        return ["hello app!"];
    }

    /**
     * @Rest\Post("/test",name="api_index_test")
     */
    public function test()
    {
        return ["hello world"];
    }
}
