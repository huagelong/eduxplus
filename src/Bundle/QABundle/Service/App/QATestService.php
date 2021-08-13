<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2021/5/12 21:36
 */

namespace App\Bundle\QABundle\Service\App;


use App\Bundle\AppBundle\Lib\Base\AppBaseService;
use App\Bundle\AppBundle\Service\CategoryService;
use App\Bundle\AppBundle\Service\TeacherService;
use Knp\Component\Pager\PaginatorInterface;

class QATestService extends AppBaseService
{

    protected $categoryService;
    protected $paginator;

    public function __construct(CategoryService $categoryService, PaginatorInterface $paginator)
    {
        $this->categoryService = $categoryService;
        $this->paginator = $paginator;
    }

    public function getCategoryGoods($categoryId, $level, $isFree, $page=1,$pageSize=20)
    {
        $shopPriceStr = "";
        if($isFree==1){
            $shopPriceStr = " AND a.shopPrice=0";
        }else if($isFree==2){
            $shopPriceStr = " AND a.shopPrice>0";
        }

        if ($level == 2) {
            $sql = "SELECT a FROM App:MallGoods a WHERE a.categoryId=:categoryId ".$shopPriceStr." AND a.status=1  AND  a.goodType=2  ORDER BY a.sort DESC";
            $categoryIds = $categoryId;
        } else {
            $subCates = $this->categoryService->getSubsCategory($categoryId);
            $categoryIds = $subCates ? array_column($subCates, "id") : [];
            if ($categoryIds) {
                array_push($categoryIds, $categoryId);
            } else {
                $categoryIds = [$categoryId];
            }
            $sql = "SELECT a FROM App:MallGoods a WHERE a.categoryId IN (:categoryId)  ".$shopPriceStr." AND a.status=1 AND  a.goodType=2  ORDER BY a.sort DESC";
        }

        $em = $this->getDoctrine()->getManager();
        $em = $this->enableSoftDeleteable($em);
        $query = $em->createQuery($sql);
        $query = $query->setParameters(["categoryId" => $categoryIds]);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        if (!$items) return [$pagination, []];
        $itemsArr = [];
        foreach ($items as $v) {
            $vArr =  $this->toArray($v);
            $vArr['tagsArr'] = explode(",", $vArr['tags']);
            $vArr['shopPriceView'] = number_format($vArr['shopPrice'] / 100, 2);
            $itemsArr[]= $vArr;
        }

        return [$pagination,$itemsArr];
    }

    /**
     *  用户试卷列表
     */
    public function getList($uid, $page, $pageSize){
        $time = time();
        $dql = "SELECT a FROM QA:TeachTestOrder a WHERE a.uid=:uid AND a.orderStatus=2 ORDER BY a.createdAt ASC";
        $em = $this->getDoctrine()->getManager();
        $em = $this->enableSoftDeleteable($em);
        $query = $em->createQuery($dql);
        $query = $query->setParameters(["uid" => $uid]);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if ($items) {
            foreach ($items as $v) {
                $vArr =  $this->toArray($v);
                $testId = $vArr['testId'];
                $sql = "SELECT a FROM QA:TeachTest a WHERE a.id=:id";
                $testInfo =  $this->fetchOne($sql, ['id' => $testId]);
                $vArr['testInfo'] = $testInfo;
                $itemsArr[] = $vArr; 
            }
        }
        return [$pagination, $itemsArr];
    }


    public function getTestById($testId){
        $testSql = "SELECT a FROM QA:TeachTest a WHERE a.id=:id ";
        $testInfo = $this->fetchOne($testSql, ["id"=>$testId]);
        return $testInfo;
    }


    /**
     * 试题
     */
    public function getTest($testId){
        $sql = "SELECT a.qaNodeId FROM QA:TeachTestSub a WHERE a.testId=:testId ORDER BY a.type ASC, a.sort ASC ";
        $qaNodeIds = $this->fetchFields("qaNodeId", $sql, ["testId"=>$testId]);
        if(!$qaNodeIds) return [];
        $sqlNodes = "SELECT a FROM QA:TeachQANode a WHERE a.id IN (:id) AND a.status=1 ";
        $nodesInfo = $this->fetchAll($sqlNodes, ["id"=>$qaNodeIds]);
        if(!$nodesInfo) return [];
        $realNodes = [];
        foreach($nodesInfo as $info){
            $realNodes[] = $info["id"];
        }
        $sqlNodes = "SELECT a FROM QA:TeachQANodeSub a WHERE a.qaNodeId IN (:qaNodeId)";
        $nodesSubInfo = $this->fetchAll($sqlNodes, ["qaNodeId"=>$realNodes]);
        if(!$nodesSubInfo) return [];
        $result = [];
        foreach($nodesInfo as $info){
            foreach($nodesSubInfo as $sub){
                    if($info["id"] == $sub["qaNodeId"]){
                        $info["sub"] = $sub;
                        $result[$info["type"]][] = $info;
                    }
            }
        }
       return $result;
    }

    /**
     * 收藏的试题
     */
    public function myNodeFav($uid, $page, $pageSize){
        
    }

}
