<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/12/6 19:24
 */

namespace Eduxplus\QaBundle\Service\Admin;


use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\QaBundle\Entity\TeachTest;
use Eduxplus\QaBundle\Entity\TeachTestSub;
use Knp\Component\Pager\PaginatorInterface;

class QATestSubService  extends AdminBaseService
{
    protected $paginator;
    protected $userService;
    protected $categoryService;


    public function __construct(PaginatorInterface $paginator, UserService $userService, CategoryService $categoryService)
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->categoryService = $categoryService;
    }

    public function getList($request, $page, $pageSize, $id){
        $sql = $this->getFormatRequestSql($request);

        if($sql){
            $sql .= " AND a.testId=:testId ";
        }else{
            $sql = " WHERE a.testId=:testId ";
        }

        $dql = "SELECT a FROM Qa:TeachTestSub a " . $sql . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);

        $query = $query->setParameters(["testId"=>$id]);

        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];
        if ($items) {
            foreach ($items as $v) {
                $vArr = $this->toArray($v);
                $nodeSql = "SELECT a FROM Qa:TeachQANode a WHERE a.id=:id ";
                $nodeInfo = $this->db()->fetchOne($nodeSql, ["id"=>$vArr['qaNodeId']]);
                $vArr['topic'] = $nodeInfo['topic'];
                $itemsArr[] = $vArr;
            }
        }
        return [$pagination, $itemsArr];
    }


    public function add($uid, $name, $categoryId, $sort,$status){
        $model = new TeachTest();
        $model->setCreateUid($uid);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setName($name);
        $model->setSort($sort);
        return $this->db()->save($model);
    }

    public function edit($id, $name, $categoryId, $sort,$status){
        $sql = "SELECT a FROM Qa:TeachTest a WHERE a.id=:id ";
        $model = $this->db()->fetchOne($sql, ['id'=>$id], 1);
        $model->setStatus($status);
        $model->setCategoryId($categoryId);
        $model->setName($name);
        $model->setSort($sort);
        return $this->db()->save($model);
    }


    public function del($id){
        $sql = "SELECT a FROM Qa:TeachTestSub a WHERE a.id=:id ";
        $model = $this->db()->fetchOne($sql, ['id'=>$id], 1);
        $this->db()->delete($model);
        return true;
    }

    public function getById($id){
        $sql = "SELECT a FROM Qa:TeachTestSub a WHERE a.id=:id";
        return $this->db()->fetchOne($sql, ['id' => $id]);
    }


    public function getNodeByIds($nodeIds){

        $dql = "SELECT a FROM Qa:TeachQANode a WHERE a.id IN(:id) ORDER BY FIELD(a.type,0,1,2,3,4,5,6), a.id DESC";
        $items = $this->db()->fetchAll($dql, ["id"=>$nodeIds]);

        $types = [0=>"单项选择",1=>"多项选择",2=>"不定项选择题",3=>"判断题",4=>"填空题",5=>"问答题",6=>"理解题"];
        $levels = [0=>"容易",1=>"一般",2=>"困难"];
        $nTypes = [0=>"常考题",1=>"易错题",2=>"好题",3=>"压轴题"];

        $result = [];
        if ($items) {
            foreach ($items as $vArr) {
                $vArr['typeName'] = $types[$vArr['type']];
                $vArr['level'] = $levels[$vArr['level']];
                $vArr['nType'] = $nTypes[$vArr['nodeType']];

                $sql = "SELECT a FROM Qa:TeachQANodeSub a WHERE a.qaNodeId=:qaNodeId";
                $nodeSub = $this->db()->fetchOne($sql, ['qaNodeId' => $vArr['id']]);
                $vArr['nodeSub'] = $nodeSub;

                //章节点
                $chapterSubSql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.id=:id";
                $chapterSubName = $this->db()->fetchField("name",$chapterSubSql, ["id"=>$vArr['chapterSubId']]);
                $vArr['chapterSubName'] = $chapterSubName;

                $result[$vArr['typeName']][] = $vArr;
            }
        }

        return $result;
    }

    public function getNodeList($request, $page, $pageSize, $defaultCategoryId){
        $sql = $this->getFormatRequestSql($request,["a.categoryId"]);

        $fields = $request->query->all();
        $values = isset($fields['values']) ? $fields['values']:[];
        $categoryId = isset($values['a.categoryId'])?$values['a.categoryId']:0;
        $chapterId = isset($values['a.chapterId'])?$values['a.chapterId']:0;
        $chapterSubId =  isset($values['a.chapterSubId'])?$values['a.chapterSubId']:0;

        $checkCategory = 0;
        if(!$chapterId && !$chapterSubId){
            if($categoryId){
                $checkCategory = 1;
            }elseif($defaultCategoryId){
                $categoryId = $defaultCategoryId;
                $checkCategory = 1;
            }
        }

        if($sql){
            $sql .= " AND a.status=1 ";
        }else{
            $sql = " WHERE a.status=1 ";
        }

        if($checkCategory){
            $subCategoryIds = $this->categoryService->getSubCategoryIds($categoryId);
            array_push($subCategoryIds, $categoryId);
            $chapterIdSql = "SELECT a FROM Qa:TeachQAChapter a WHERE a.categoryId IN(:categoryId) AND a.status=1 ";
            $chapterIds = $this->db()->fetchFields("id", $chapterIdSql, ["categoryId"=>$subCategoryIds]);
            $sql .= " AND a.chapterId IN(:chapterId) ";
        }

        $dql = "SELECT a FROM Qa:TeachQANode a " . $sql . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
//        var_dump($dql);
        if($checkCategory){
            $query = $query->setParameters(["chapterId"=>$chapterIds]);
        }

        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $pageSize
        );

        $items = $pagination->getItems();
        $itemsArr = [];

        $types = [0=>"单项选择",1=>"多项选择",2=>"不定项选择题",3=>"判断题",4=>"填空题",5=>"问答题",6=>"理解题"];
        $levels = [0=>"容易",1=>"一般",2=>"困难"];
        $nTypes = [0=>"常考题",1=>"易错题",2=>"好题",3=>"压轴题"];

        if ($items) {
            foreach ($items as $v) {
                $vArr = $this->toArray($v);

                $vArr['typeName'] = $types[$vArr['type']];
                $vArr['level'] = $levels[$vArr['level']];
                $vArr['nType'] = $nTypes[$vArr['nodeType']];
                $vArr['createdAt'] = $vArr["createdAt"];

                $sql = "SELECT a FROM Qa:TeachQANodeSub a WHERE a.qaNodeId=:qaNodeId";
                $nodeSub = $this->db()->fetchOne($sql, ['qaNodeId' => $vArr['id']]);
                $vArr['nodeSub'] = $nodeSub;

                //章节点
                $chapterSubSql = "SELECT a FROM Qa:TeachQAChapterSub a WHERE a.id=:id";
                $chapterSubName = $this->db()->fetchField("name",$chapterSubSql, ["id"=>$vArr['chapterSubId']]);
                $vArr['chapterSubName'] = $chapterSubName;

                $createrUid = $vArr['createUid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];

                $itemsArr[] = $vArr;
            }
        }

        return [$pagination, $itemsArr];
    }

    public function getAllNodeIds($testId){
        $sql = "SELECT a FROM Qa:TeachTestSub a WHERE a.testId =:testId ";
        return $this->db()->fetchFields("qaNodeId", $sql, ["testId"=>$testId]);
    }

    /**
     * 添加试题
     * @param $testId
     * @param $nodeIds
     * @param $types
     * @param $sorts
     */
    public function mgNode($testId, $nodeIds, $types, $sorts){

        try{
            $this->db()->beginTransaction();
            //先删除
            $sql = "DELETE FROM Qa:TeachTestSub a WHERE a.testId=:testId";
            $this->db()->execute($sql, ["testId" => $testId]);

            foreach ($nodeIds as $k=>$nodeId){
                $sort = $sorts[$k];
                $type = $types[$k];
                $model = new TeachTestSub();
                $model->setSort($sort);
                $model->setQaNodeId($nodeId);
                $model->setType($type);
                $model->setTestId($testId);
                $this->db()->save($model);
            }

            //更新试卷分数
            $totalScoreSql = "SELECT SUM(a.score) as cnt FROM Qa:TeachQANodeSub a WHERE a.qaNodeId IN(:qaNodeId) ";
            $totalScore = $this->db()->fetchField("cnt", $totalScoreSql, ["qaNodeId"=>$nodeIds]);
            $testModel = $this->db()->fetchOne("SELECT a FROM Qa:TeachTest a WHERE a.id=:id", ["id"=>$testId], 1);
            $testModel->setScore($totalScore);
            $this->db()->update($testModel);
            $this->db()->commit();
            return true;
        }catch (\Exception $e){
            $this->db()->rollback();
            return $this->error()->add($e->getMessage());
        }
    }

    /**
     * 试卷是否有人做过
     * @param $testId
     */
    public function hasTestDo($testId){
        $sql = "SELECT count(a.id) as cnt FROM Qa:TeachTestAnswer a WHERE a.testId=:testId ";
        $count = $this->db()->fetchField("cnt", $sql, ["testId"=>$testId]);
        return $count;
    }
}
