<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2021/4/13 10:42
 */

namespace App\Bundle\QABundle\Controller\Admin;


use App\Bundle\AdminBundle\Service\Mall\GoodsService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use App\Bundle\QABundle\Service\Admin\QATestService;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class GlobController extends BaseAdminController
{

    /**
     * 试卷商品搜索
     *
     * @Rest\Get("/glob/searchProduct/do", name="admin_qa_api_glob_searchProductDo")
     */
    public function searchProductDoAction(Request $request, QATestService $testService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $testService->searchProductName($kw);
        return $data;
    }


    /**
     * @Rest\Get("/glob/searchGoods/do", name="admin_qa_api_glob_searchGoodsDo")
     */
    public function searchGoodsDoAction(Request $request, GoodsService $goodsService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $goodsService->searchGoodsName($kw, 2);
        return $data;
    }

}
