<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/26 10:18
 */

namespace App\Bundle\AppBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use App\Bundle\AppBundle\Service\CategoryService;
use App\Bundle\AppBundle\Service\GoodsService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class DetailController extends BaseHtmlController
{

    /**
     * @Rest\Get("/detail/{uuid}", name="app_detail_index")
     */
    public function indexAction($uuid, GoodsService $goodsService){
        $detail = $goodsService->getByUuId($uuid);
        $id = $detail['id'];

        $data = [];
        $data["firstId"] = $detail["firstCategoryId"];
        $data["info"] = $detail;
        $data['studyPlan'] = $goodsService->getStudyPlan($id);
        $uid = $this->getUid();
        $data['fav'] = [];
        if($uid){
            $data['fav'] = $goodsService->getFav($id, $uid);
        }
        return $this->render("@AppBundle/detail/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/center/{categoryId<\d+>?0}/{isFree<\d+>?0}", name="app_detail_center", defaults={"categoryId":"0", "isFree":"0"})
     */
    public function centerAction($categoryId,$isFree,Request $request, CategoryService $categoryService, GoodsService $goodsService){
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

        list($pagination, $list) = $goodsService->getCategoryGoods($categoryId, $level, $isFree, $page, $pageSize);
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
        $data['route'] = "app_detail_center";
        $data['pagination'] = $pagination;
        return $this->render("@AppBundle/detail/center.html.twig", $data);
    }

    /**
     * @Rest\Get("/detail/my/do/doFav/{uuid}", name="app_detail_doFav")
     */
    public function doFavAction($uuid, GoodsService $goodsService){
        $uid = $this->getUid();
        $goodInfo = $goodsService->getSimpleByUuid($uuid);
        $goodsId = $goodInfo['id'];
        $check = $goodsService->getFav($goodsId, $uid);
        if($check){
            $goodsService->delFav($goodsId, $uid);
        }else{
            $goodsService->addFav($goodsId, $uid);
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl("app_detail_index", ["uuid"=>$uuid]));
    }
}
