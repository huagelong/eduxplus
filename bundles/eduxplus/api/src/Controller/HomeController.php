<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace Eduxplus\ApiBundle\Controller;

use Eduxplus\ApiBundle\Service\GoodService;
use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package Eduxplus\ApiBundle\Controller
 */
class HomeController extends BaseApiController
{
    
    public function homePageAction(GoodService $goodService)
    {
        $data = $goodService->getHomeData();
        return $data;
    }
}
