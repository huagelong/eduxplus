<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\ApiBundle\Controller;

use App\Bundle\ApiBundle\Service\GoodService;
use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Bundle\ApiBundle\Controller
 */
class GoodController extends BaseApiController
{

    /**
     * @Rest\Post("/cateGoods",name="api_good_cateGoods")
     */
    public function cateGoodsAction(Request $request, GoodService $goodService)
    {
        $id = $request->get("id");

        $idArr = explode(",", $id);
        $data = [];
        if (count($idArr) > 1) {
            foreach ($idArr as $rid) {
                $rdata = $goodService->getCateGoods($rid);
                $data[$rid] = $rdata;
            }
        } else {
            $data = $goodService->getCateGoods($id);
        }
        return $data;
    }

    /**
     * @Rest\Post("/getSubCate",name="api_good_getSubCate")
     */
    public function getSubCateAction(Request $request, GoodService $goodService)
    {
        $id = $request->get("id");
        $data = $goodService->getSubCate($id);
        return $data;
    }
}
