<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 14:07
 */

namespace App\Bundle\AppBundle\Controller;

use App\Bundle\AppBundle\Lib\Base\BaseHtmlController;
use App\Bundle\AppBundle\Lib\Service\Vod\BokeccService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseHtmlController
{

    /**
     * @Rest\Get("/", name="app_glob_index")
     */
    public function indexAction(BokeccService $bokeccService){
        $rs = $bokeccService->searchVideo("手把手", "BE59FAE6BE3646CE");
        dump($rs);
        exit;
    }
}
