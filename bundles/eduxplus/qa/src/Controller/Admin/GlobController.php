<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2021/4/13 10:42
 */

namespace Eduxplus\QaBundle\Controller\Admin;


use Eduxplus\EduxBundle\Service\Mall\GoodsService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\QaBundle\Service\Admin\QATestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GlobController extends BaseAdminController
{

    
    public function searchProductDoAction(Request $request, QATestService $testService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $testService->searchProductName($kw);
        return $data;
    }


    
    public function searchGoodsDoAction(Request $request, GoodsService $goodsService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $goodsService->searchGoodsName($kw, 2);
        return $data;
    }

}
