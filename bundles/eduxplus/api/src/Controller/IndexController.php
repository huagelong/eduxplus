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

/**
 * @package Eduxplus\ApiBundle\Controller
 */
class IndexController extends BaseApiController
{
    
    public function index()
    {
        return ["hello app!"];
    }

    
    public function test()
    {
        return ["hello world"];
    }
}
