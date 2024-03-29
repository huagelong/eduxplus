<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2021/1/16 09:17
 */

namespace Eduxplus\QaBundle\Controller\App;


use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\WebsiteBundle\Service\CategoryService;
use Eduxplus\WebsiteBundle\Service\GoodsService;
use Eduxplus\QaBundle\Service\App\QATestService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
class IndexController extends BaseHtmlController
{

    
    public function indexAction($categoryId=0,$isFree=0,Request $request, CategoryService $categoryService, QATestService $qaTestService){
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

        list($pagination, $list) = $qaTestService->getCategoryGoods($categoryId, $level, $isFree, $page, $pageSize);
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

        return $this->render("@QaBundle/exam/index.html.twig", $data);
    }

    
    public function buyAction($uuid, GoodsService $goodsService){
        $detail = $goodsService->getByUuId($uuid);
        $id = $detail['id'];

        $data = [];
        $data["firstId"] = $detail["firstCategoryId"];
        $data["info"] = $detail;
        $data['studyPlan'] = $goodsService->getStudyPlan($id);
        $uid = $this->getUid();
        $data['fav'] = [];
        return $this->render("@QaBundle/exam/buy.html.twig", $data);
    }

    
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
        return $this->render("@QaBundle/exam/mytest.html.twig", $data);
    }


     
    public function testDetailAction($id, QATestService $qaTestService){
        $data = [];
        $data["testInfo"] = $qaTestService->getTestById($id);
        $data["testNode"] = $qaTestService->getTest($id);

        return $this->render("@QaBundle/exam/testDetail.html.twig", $data);
    }


    
    public function testInitAction($id, QATestService $qaTestService){
        $data = [];
        $data["testInfo"] = $qaTestService->getTestById($id);
//        dump($data);exit;
        return $this->render("@QaBundle/exam/testInit.html.twig", $data);
    }


    
    public function testToDoAction($id, QATestService $qaTestService){
        $data = [];
        $data["testInfo"] = $qaTestService->getTestById($id);
        $data["testNode"] = $qaTestService->getTest($id);
        // $this->logger()->info("test-testToDoAction");
        // $logger = $this->get('logger');
        return $this->render("@QaBundle/exam/testTodo.html.twig", $data);
    }

    
    public function submitAnswerLogAction(QATestService $qaTestService){
        $testId = $this->request()->request->get("testId");
        $nodeId = $this->request()->request->get("nodeId");
        $uid = $this->getUid();
        $answer = $this->request()->request->get("answer");
        if(!$testId) return $this->responseError("参数有误testId!");
        if(!$nodeId) return $this->responseError("参数有误nodeId!");
        if(!$answer) return $this->responseError("参数有误answer!");

        $answerInfo = $qaTestService->saveAnswerLog($testId, $nodeId, $uid, $answer);

        return $this->responseSuccess($answerInfo);
    }

    
    public function submitAnswerAction($id, QATestService $qaTestService){
        $request = $this->request()->request->all();
        $uid = $this->getUid();
        $answerId = $qaTestService->submitAnswer($id, $request, $uid);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("提交成功!", $this->generateUrl("qa_test_answer_view", ["id"=>$answerId]));
    }

    
    public function answerViewAction($id, QATestService $qaTestService){
        $data = [];
        $data['info'] = $qaTestService->getAnswerById($id);
        return $this->render("@QaBundle/exam/answerView.html.twig", $data);
    }


}
