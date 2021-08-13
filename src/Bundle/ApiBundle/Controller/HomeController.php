<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\ApiBundle\Controller;

use App\Bundle\ApiBundle\Service\GoodService;
use App\Bundle\AppBundle\Lib\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Bundle\ApiBundle\Controller
 */
class HomeController extends BaseApiController
{
    /**
     * @Rest\Post("/homePage",name="api_home_homePage")
     */
    public function homePageAction(GoodService $goodService)
    {
        $data = $goodService->getHomeData();
        return $data;
    }
}
