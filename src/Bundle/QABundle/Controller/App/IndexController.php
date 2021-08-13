<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2021/1/16 09:17
 */

namespace App\Bundle\QABundle\Controller\App;


use App\Bundle\AppBundle\Lib\Base\BaseHtmlController;
use App\Bundle\AppBundle\Service\CategoryService;
use App\Bundle\AppBundle\Service\GoodsService;
use App\Bundle\QABundle\Service\App\QATestService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseHtmlController
{

    /**
     * @Rest\Get("/exam/{categoryId<\d+>?0}/{isFree<\d+>?0}", name="qa_exam_index")
     */
    public function indexAction($categoryId=0,$isFree=0,Request $request, CategoryService $categoryService, QATestService $QATestService){
        $data = [];

        $page = $request->get("page");
        $page = $page?$page:1;
        $pageSize = 40;

        $secondSubCategorys = [];
        $threeSubCategory = [];
        $firstId = 0;
        $secondId = 0;
        $threeId = 0;
        $level = 0;

        $brand = $categoryService->getBrands();
        if ($categoryId) {
            $categoryInfo = $categoryService->getCategory($categoryId);
            if (!$categoryInfo['findPath']) { //id是第一级
                $firstId = $categoryId;
                //获取第二级
                $secondSubCategorys = $categoryService->getSubCategory($firstId);
            } else {
                $path = trim($categoryInfo['findPath'], ",");
                $pathArr = explode(",", $path);
                if (count($pathArr) == 1) { // id是二级
                    $firstId = $categoryInfo["parentId"];
                    $secondSubCategorys = $categoryService->getSubCategory($firstId);
                    $secondId = $categoryId;
                    $threeSubCategory = $categoryService->getSubCategory($secondId);
                    $threeId = 0;
                    $level = 1;
                } else { // id是三级
                    list($secondId, $firstId) = $pathArr;
                    $threeId = $categoryId;
                    $level = 2;
                }
            }
        }

        if (!$secondSubCategorys) $secondSubCategorys = $categoryService->getSubCategory($firstId);
        if (!$threeSubCategory) $threeSubCategory = $categoryService->getSubCategory($secondId);

        list($pagination, $list) = $QATestService->getCategoryGoods($categoryId, $level, $isFree, $page, $pageSize);
        $data = [];
        $data['categoryId'] = $categoryId;
        $data['firstId'] = $firstId;
        $data['secondId'] = $secondId;
        $data['threeId'] = $threeId;
        $data['brands'] = $brand;
        $data['id'] = $categoryId;
        $data['isFree'] = $isFree;
        $data["secondSubCategorys"] = $secondSubCategorys;
        $data["threeSubCategory"] = $threeSubCategory;
        $data['list'] = $list;
        $data['route'] = "qa_exam_index";
        $data['pagination'] = $pagination;

        return $this->render("@QABundle/exam/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/mall/detail/{uuid}", name="qa_mall_detail_index")
     */
    public function detailAction($uuid, GoodsService $goodsService){
        $detail = $goodsService->getByUuId($uuid);
        $id = $detail['id'];

        $data = [];
        $data["firstId"] = $detail["firstCategoryId"];
        $data["info"] = $detail;
        $data['studyPlan'] = $goodsService->getStudyPlan($id);
        $uid = $this->getUid();
        $data['fav'] = [];
        return $this->render("@QABundle/exam/detail.html.twig", $data);
    }

    /**
     * 我的试卷
     *
     * @Rest\Get("/my/test", name="qa_mytest")
     */
    public function mytestAction(Request $request, QATestService $qaTestService){
        $route = $request->get("_route");
        $page = $request->get("page");
        $page = $page?$page:1;
        $pageSize = 20;
        $uid = $this->getUid();
        list($pagination, $list) =  $qaTestService->getList($uid,$page,$pageSize);
        $data = [];
        $data['page'] = $page;
        $data['route'] = $route;
        $data['list'] = $list;
        $data['route'] = "qa_mytest";
        $data['pagination'] = $pagination;
        return $this->render("@QABundle/exam/mytest.html.twig", $data);
    }


     /**
     * 做试卷
     *
     * @Rest\Get("/test/detail-{id}", name="qa_test_detail")
     */
    public function dotestAction($id, QATestService $qaTestService){
        $data = [];
        $data["testInfo"] = $qaTestService->getTestById($id);
        $data["testNode"] = $qaTestService->getTest($id);
        return $this->render("@QABundle/exam/testDetail.html.twig", $data);
    }

}
