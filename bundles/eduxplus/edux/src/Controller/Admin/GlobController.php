<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 15:41
 */

namespace Eduxplus\EduxBundle\Controller\Admin;


use Eduxplus\EduxBundle\Service\Mall\GoodsService;
use Eduxplus\EduxBundle\Service\Teach\ProductService;
use Eduxplus\EduxBundle\Service\Teach\StudyPlanService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\CoreBundle\Lib\Base\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GlobController extends BaseAdminController
{
  
    
    public function searchProductDoAction(Request $request, ProductService $productService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $productService->searchProductName($kw);
        return $this->responseSuccess($data);
    }

    
    public function searchGoodsDoAction(Request $request, GoodsService $goodsService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $goodsService->searchGoodsName($kw);
        return $this->responseSuccess($data);
    }

    
    public function searchCourseDoAction(Request $request, StudyPlanService $studyPlanService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $studyPlanService->searchCourseName($kw);
        return $this->responseSuccess($data);
    }


}
