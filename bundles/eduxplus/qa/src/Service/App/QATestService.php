<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2021/5/12 21:36
 */

namespace Eduxplus\QaBundle\Service\App;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\WebsiteBundle\Service\CategoryService;
use Eduxplus\WebsiteBundle\Service\TeacherService;
use Eduxplus\QaBundle\Entity\TeachTestAnswer;
use Eduxplus\QaBundle\Entity\TeachTestAnswerLog;
use Elasticsearch\Endpoints\Indices\Split;
use Error;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;

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
            $sql = "SELECT a FROM Core:MallGoods a WHERE a.categoryId=:categoryId ".$shopPriceStr." AND a.status=1  AND  a.goodType=2  ORDER BY a.sort DESC";
            $categoryIds = $categoryId;
        } else {
            $subCates = $this->categoryService->getSubsCategory($categoryId);
            $categoryIds = $subCates ? array_column($subCates, "id") : [];
            if ($categoryIds) {
                array_push($categoryIds, $categoryId);
            } else {
                $categoryIds = [$categoryId];
            }
            $sql = "SELECT a FROM Core:MallGoods a WHERE a.categoryId IN (:categoryId)  ".$shopPriceStr." AND a.status=1 AND  a.goodType=2  ORDER BY a.sort DESC";
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
        $dql = "SELECT a FROM Qa:TeachTestOrder a WHERE a.uid=:uid AND a.orderStatus=2 ORDER BY a.createdAt ASC";
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
                $sql = "SELECT a FROM Qa:TeachTest a WHERE a.id=:id";
                $testInfo =  $this->fetchOne($sql, ['id' => $testId]);
                $vArr['testInfo'] = $testInfo;
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }


    public function getTestById($testId){
        $testSql = "SELECT a FROM Qa:TeachTest a WHERE a.id=:id ";
        $testInfo = $this->fetchOne($testSql, ["id"=>$testId]);

        return $testInfo;
    }


    /**
     * 试题
     */
    public function getTest($testId){
        $sql = "SELECT a.qaNodeId FROM Qa:TeachTestSub a WHERE a.testId=:testId ORDER BY a.type ASC, a.sort ASC ";
        $qaNodeIds = $this->fetchFields("qaNodeId", $sql, ["testId"=>$testId]);
        if(!$qaNodeIds) return [];
        $qaNodeIdsStr = implode(",", $qaNodeIds);
        $sqlNodes = "SELECT a FROM Qa:TeachQANode a WHERE a.id IN (:id) AND a.status=1 ORDER BY FIELD(a.id,".$qaNodeIdsStr.")";
        $nodesInfo = $this->fetchAll($sqlNodes, ["id"=>$qaNodeIds]);
        if(!$nodesInfo) return [];
        $realNodes = [];
        foreach($nodesInfo as $info){
            $realNodes[] = $info["id"];
        }
        $realNodesStr = implode(",", $realNodes);
        $sqlNodes = "SELECT a FROM Qa:TeachQANodeSub a WHERE a.qaNodeId IN (:qaNodeId) ORDER BY FIELD(a.qaNodeId,".$realNodesStr.")";

        $nodesSubInfo = $this->fetchAll($sqlNodes, ["qaNodeId"=>$realNodes]);
        if(!$nodesSubInfo) return [];

        //做题记录
        $sqlAnswerLog = "SELECT a FROM Qa:TeachTestAnswerLog a WHERE a.testId=:testId ";
        $answerLogList = $this->fetchAll($sqlAnswerLog, ["testId"=>$testId]);
       
        foreach($nodesInfo as $k=>$info){
            foreach($nodesSubInfo as &$sub){
                    if($info["id"] == $sub["qaNodeId"]){
                        if($info["type"] == 4){
                            $answer = $sub["answer"];
                            $answer = str_replace("\|", chr(0), $answer);
                            $answer = explode("|", $answer);
                            $sub["count"] = count($answer)-1;
                        }
                        $nodesInfo[$k]["sub"] = $sub;
                        break;
                    }
            }
            
            //回答日志库
            if($answerLogList){
                foreach($answerLogList as $answerLog){
                    if($info["id"] == $answerLog["qaNodeId"]){
                        $nodesInfo[$k]["log"] = $answerLog;
                        break;
                    }
                }
            }

        }
        
        $result = [];
        foreach($nodesInfo as $info){
            $result[$info["type"]][] = $info;
        }
 
       return $result;
    }

    /**
     * 收藏的试题
     */
    public function myNodeFav($uid, $page, $pageSize){

    }

    /**
     * 保存做题记录
     */
    public function saveAnswerLog($testId, $nodeId, $uid, $answer){
        $sql = "SELECT a FROM Qa:TeachTestAnswerLog a WHERE a.testId=:testId AND a.qaNodeId=:qaNodeId AND a.uid=:uid";
        $info = $this->fetchOne($sql, ["testId"=>$testId, "qaNodeId"=>$nodeId, "uid"=>$uid],1);
        if($info){
            $info->setAnswer($answer);
            $this->save($info);
        }else{
            $model = new TeachTestAnswerLog();
            $model->setTestId($testId);
            $model->setQaNodeId($nodeId);
            $model->setUid($uid);
            $model->setAnswer($answer);
            $this->save($model);
        }
    }

    /**
     * 提交答案
     */
    public function submitAnswer($testId, $params, $uid){
        //循环test题目获取题目内容
        $sql = "SELECT a.qaNodeId FROM Qa:TeachTestSub a WHERE a.testId=:testId ORDER BY a.type ASC, a.sort ASC ";
        $qaNodeIds = $this->fetchFields("qaNodeId", $sql, ["testId"=>$testId]);
        if(!$qaNodeIds) return [];
        $qaNodeIdsStr = implode(",", $qaNodeIds);
        $sqlNodes = "SELECT a FROM Qa:TeachQANode a WHERE a.id IN (:id) AND a.status=1 ORDER BY FIELD(a.id,".$qaNodeIdsStr.")";
        $nodesInfo = $this->fetchAll($sqlNodes, ["id"=>$qaNodeIds]);
        if(!$nodesInfo) return [];
        $realNodes = [];
        foreach($nodesInfo as $info){
            $realNodes[] = $info["id"];
        }
        $realNodesStr = implode(",", $realNodes);
        $sqlNodes = "SELECT a FROM Qa:TeachQANodeSub a WHERE a.qaNodeId IN (:qaNodeId) ORDER BY FIELD(a.qaNodeId,".$realNodesStr.")";

        $nodesSubInfo = $this->fetchAll($sqlNodes, ["qaNodeId"=>$realNodes]);
        if(!$nodesSubInfo) return [];
        $result = [];
        $totalErrorNum=0;
        $totalRightNum=0;
        $undoNum=0;
        $totalScore = 0;
        //循环处理答案
        foreach($nodesInfo as &$info){
            $result[$info["id"]] = ["status"=>0,"answer"=>""];
            foreach($nodesSubInfo as $sub){
                    if($info["id"] == $sub["qaNodeId"]){
                        $type = $info["type"];
                        $requestKey = "tk-".$type."-".$info["id"];
                        $requestAnswer = isset($params[$requestKey])?$params[$requestKey]:"";
                        $answer = $sub["answer"];
                        if(!$requestAnswer){
                            $totalErrorNum = $totalErrorNum+1;
                            $result[$info["id"]] = ["status"=>0,"answer"=>$requestAnswer];
                            $undoNum = $undoNum+1;
                        }else{
                            if($type == 0){ //单选题
                                //答案正确
                                if($this->eqCheck($requestAnswer, $answer)){
                                    $totalRightNum = $totalRightNum+1;
                                    $result[$info["id"]] = ["status"=>1,"answer"=>$requestAnswer];
                                    $totalScore=$totalScore+$sub["score"];
                                }else{
                                    $totalErrorNum = $totalErrorNum+1;
                                    $result[$info["id"]] = ["status"=>0,"answer"=>$requestAnswer];
                                }
                            }else if($type == 1){//多项选择
                                
                                if($requestAnswer){  //数组
                                    if($this->eqCheck($requestAnswer, $answer)){
                                        $totalRightNum = $totalRightNum+1;
                                        $result[$info["id"]] = ["status"=>1,"answer"=>$requestAnswer];
                                        $totalScore=$totalScore+$sub["score"];
                                    }else{
                                        $totalErrorNum = $totalErrorNum+1;
                                        $result[$info["id"]] = ["status"=>0,"answer"=>$requestAnswer];
                                    }
                                }
    
                            }else if($type == 2){//不定项选择题
                                if($requestAnswer){  //数组
                                    if($this->eqCheck($requestAnswer, $answer)){
                                        $totalRightNum = $totalRightNum+1;
                                        $result[$info["id"]] = ["status"=>1,"answer"=>$requestAnswer];
                                        $totalScore=$totalScore+$sub["score"];
                                    }else{
                                        $totalErrorNum = $totalErrorNum+1;
                                        $result[$info["id"]] = ["status"=>0,"answer"=>$requestAnswer];
                                    }
                                }
    
                            }else if($type == 3){//判断题
                                    //答案正确
                                    if($this->eqCheck($requestAnswer, $answer)){
                                        $totalRightNum = $totalRightNum+1;
                                        $result[$info["id"]] = ["status"=>1,"answer"=>$requestAnswer];
                                        $totalScore=$totalScore+$sub["score"];
                                    }else{
                                        $totalErrorNum = $totalErrorNum+1;
                                        $result[$info["id"]] = ["status"=>0,"answer"=>$requestAnswer];
                                    }
                            }else if($type == 4){//填空题
                                //需要单独处理
                                list($status, $subScore) = $this->kwMutiCheck($requestAnswer, $answer, $sub["score"]);
                                if($status == 1){
                                    $totalRightNum = $totalRightNum+1;
                                    $result[$info["id"]] = ["status"=>1,"answer"=>$requestAnswer];
                                    $totalScore=$totalScore+$subScore;
                                }else if($status ==2){
                                    $result[$info["id"]] = ["status"=>2,"answer"=>$requestAnswer];
                                    $totalScore=$totalScore+$subScore;
                                }else{
                                    $totalErrorNum = $totalErrorNum+1;
                                    $result[$info["id"]] = ["status"=>0,"answer"=>$requestAnswer];
                                }
                            }else if($type == 5){//问答
                                list($status, $subScore) = $this->kwCheck($requestAnswer, $answer, $sub["score"]);
                                if($status == 1){
                                    $totalRightNum = $totalRightNum+1;
                                    $result[$info["id"]] = ["status"=>1,"answer"=>$requestAnswer];
                                    $totalScore=$totalScore+$subScore;
                                }else if($status ==2){
                                    $result[$info["id"]] = ["status"=>2,"answer"=>$requestAnswer];
                                    $totalScore=$totalScore+$subScore;
                                }else{
                                    $totalErrorNum = $totalErrorNum+1;
                                    $result[$info["id"]] = ["status"=>0,"answer"=>$requestAnswer];
                                }
                            }else if($type == 6){//理解
                                list($status, $subScore) = $this->kwCheck($requestAnswer, $answer, $sub["score"]);
                                if($status == 1){
                                    $totalRightNum = $totalRightNum+1;
                                    $result[$info["id"]] = ["status"=>1,"answer"=>$requestAnswer];
                                    $totalScore=$totalScore+$subScore;
                                }else if($status ==2){
                                    $result[$info["id"]] = ["status"=>2,"answer"=>$requestAnswer];
                                    $totalScore=$totalScore+$subScore;
                                }else{
                                    $totalErrorNum = $totalErrorNum+1;
                                    $result[$info["id"]] = ["status"=>0,"answer"=>$requestAnswer];
                                }
                            }

                        }
                    
                        break;
                    }
            }
        }
        //保存到回答数据库
        $teachTestAnswer = new TeachTestAnswer();
        $teachTestAnswer->setErrorNum($totalErrorNum);
        $teachTestAnswer->setScore($totalScore);
        $teachTestAnswer->setAnswerSnapshot(json_encode($result));
        $teachTestAnswer->setRightNum($totalRightNum);
        $teachTestAnswer->setUid($uid);
        $teachTestAnswer->setUndoNum($undoNum);
        $teachTestAnswer->setTestId($testId);
        $answerId = $this->save($teachTestAnswer);

        //删除做题记录
        $delSql = "DELETE FROM Qa:TeachTestAnswerLog a WHERE a.testId=:testId AND a.uid=:uid";
        $this->execute($delSql, ["testId"=>$testId, "uid"=>$uid]);
        // dump([$answerId, $totalErrorNum, $totalRightNum, $undoNum, $totalScore]);exit;
       return $answerId;
    }


    public function getAnswerById($id){
        $sql = "SELECT a FROM Qa:TeachTestAnswer a WHERE a.id=:id";
        return $this->fetchOne($sql,["id"=>$id]);
    }

    /**
     * 相等检查
     */
    private function eqCheck($requestAnswer, $answer){

        if(is_array($requestAnswer)){
            sort($requestAnswer);
            $requestAnswerStr = implode("", $requestAnswer);
            $answer = str_replace("\|", chr(0), $answer);
            $answer = explode("|", $answer);
            sort($answer);
            $answerStr = "";
            foreach($answer as $v){
                $answerStr=$answerStr.str_replace(chr(0), "|", $v);
            }
        }else{
            $answerStr = str_replace("\|", "|", $answer);
            $requestAnswerStr = $requestAnswer;
        }
        if(strtolower($answerStr) == strtolower($requestAnswerStr)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 关键字检查
     */
    private function kwCheck($requestAnswer, $answer, $score){
            $answer = str_replace("\|", chr(0), $answer);
            $answer = str_replace("\:", chr(1), $answer);
            $answer = explode("|", $answer);
            $totalScore = 0;
            $correct = 0;
            foreach($answer as $v){
                $answerParseArr = explode(":", $v);
                $answerStr = isset($answerParseArr[0])?$answerParseArr[0]:"";
                $subScore = isset($answerParseArr[1])?$answerParseArr[1]:0;
                $answerStr = str_replace("\|", chr(0), $answerStr);
                $answerStr = str_replace("\:", chr(1), $answerStr);
                if(stristr($requestAnswer, $answerStr)){
                    $correct=$correct+1;
                    $totalScore = $totalScore+$subScore;
                }
            }
            $status = 0;
            if($correct > 0){
                if(count($answer)>$correct){
                    $status = 2; //部分正确
                }else{
                    $status = 1; //全部正确
                }
            }
            $totalScore = $totalScore>$score?$score:$totalScore;
           return [$status, $totalScore];
    }

    
    /**
     *  填空题检查 需要按照顺序检查答案
     */
    private function kwMutiCheck($requestAnswer, $answer, $score){
        $answer = str_replace("\|", chr(0), $answer);
        $answer = str_replace("\:", chr(1), $answer);
        $answer = explode("|", $answer);
        $totalScore = 0;
        $correct = 0;
        foreach($answer as $k=>$v){
            if(isset($requestAnswer[$k]) && $requestAnswer[$k]){
                $requestAnswerStr = $requestAnswer[$k];
            }else{
                continue;
            }
            $answerParseArr = explode(":", $v);
            $answerStr = isset($answerParseArr[0])?$answerParseArr[0]:"";
            $subScore = isset($answerParseArr[1])?$answerParseArr[1]:0;
            $answerStr = str_replace("\|", chr(0), $answerStr);
            $answerStr = str_replace("\:", chr(1), $answerStr);
            if(stristr($requestAnswerStr, $answerStr)){
                $correct=$correct+1;
                $totalScore = $totalScore+$subScore;
            }
        }

        $status = 0;
        if($correct > 0){
            if(count($answer)>$correct){
                $status = 2; //部分正确
            }else{
                $status = 1; //全部正确
            }
        }
        $totalScore = $totalScore>$score?$score:$totalScore;
       return [$status, $totalScore];
    }

}
