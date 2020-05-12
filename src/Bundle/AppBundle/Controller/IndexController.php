<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 14:07
 */

namespace App\Bundle\AppBundle\Controller;

use App\Bundle\AdminBundle\Service\Mall\CouponService;
use App\Bundle\AppBundle\Lib\Base\BaseHtmlController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseHtmlController
{

    /**
     * @Rest\Get("/", name="app_index")
     */
    public function indexAction(){
        $response = new Response("Hello World!");
        return  $response;
    }
}
