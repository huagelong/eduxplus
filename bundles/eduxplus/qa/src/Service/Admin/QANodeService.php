<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/12/1 19:23
 */

namespace Eduxplus\QaBundle\Service\Admin;


use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\QaBundle\Entity\TeachQANode;
use Eduxplus\QaBundle\Entity\TeachQANodeSub;
use Knp\Component\Pager\PaginatorInterface;

class QANodeService extends AdminBaseService
{
    protected $paginator;
    protected $userService;
    protected $chapterSubService;

    public function __construct(PaginatorInterface $paginator, UserService $userService, QAChapterSubService $chapterSubService)
    {
        $this->paginator = $paginator;
        $this->userService = $userService;
        $this->chapterSubService = $chapterSubService;
    }


    public function getList($request, $page, $pageSize, $chapterId){

        $sql = $this->getFormatRequestSql($request, ["a.chapterSubId"]);

        $chapterSubIds = [];
        if($sql){
            $sql .= " AND a.chapterId =:chapterId ";
        }else{
            $sql = " WHERE a.chapterId =:chapterId  ";
        }

        $fields = $request->query->all();
        $values = isset($fields['values'])?$fields['values']:[];
        $chapterSubId = isset($values["a.chapterSubId"])?$values["a.chapterSubId"]:0;
        if($chapterSubId){
            $chapterSubIds = $this->chapterSubService->getChapterSubIds($chapterSubId);
            array_push($chapterSubIds, $chapterSubId);
        }

        if($chapterSubIds){
            $sql .= " AND a.chapterSubId IN(:chapterSubId) ";
        }

        $dql = "SELECT a FROM Qa:TeachQANode a " . $sql . " ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        if($chapterSubIds){
            $query = $query->setParameters(["chapterId"=>$chapterId, "chapterSubId"=>$chapterSubIds]);
        }else{
            $query = $query->setParameters(["chapterId"=>$chapterId]);
        }


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
                //分数
                $vArr['score'] = $nodeSub['score'];
                $itemsArr[] = $vArr;
            }
        }

        return [$pagination, $itemsArr];
    }

    public function add($choose,$answer,$uid,$chapterId, $chapterSubId,$type, $status, $level, $nodeType, $year, $knowledge, $source,$topic,$analysis,$score){
            //类选择题，答案都小写
            if(in_array($type, [0,1,2])){
                    $answer = strtolower($answer);
            }

            $model = new TeachQANode();
            $model->setCreateUid($uid);
            $model->setChapterId($chapterId);
            $model->setChapterSubId($chapterSubId);
            $model->setType($type);
            $model->setStatus($status);
            $model->setLevel($level);
            $model->setNodeType($nodeType);
            $model->setYear($year);
            $model->setSource($source);
            $model->setTopic($topic);
            $nodeId = $this->db()->save($model);
            if(!$nodeId) return $this->error()->add("添加失败!");
            $options = json_encode($choose);
            $subModel = new TeachQANodeSub();
            $subModel->setAnalysis($analysis);
            $subModel->setAnswer($answer);
            $subModel->setKnowledge($knowledge);
            $subModel->setQaNodeId($nodeId);
            $subModel->setOptions($options);
            $subModel->setScore($score);
            $this->db()->save($subModel);
            return $nodeId;
    }

    public function edit($id, $choose,$answer,$chapterId, $chapterSubId,$type, $status, $level, $nodeType, $year, $knowledge, $source,$topic,$analysis,$score){
        //类选择题，答案都小写
         if(in_array($type, [0,1,2])){
            $answer = strtolower($answer);
        }

        $sql = "SELECT a FROM Qa:TeachQANode a WHERE a.id=:id ";
        $model= $this->db()->fetchOne($sql, ["id"=>$id], 1);
        $model->setChapterId($chapterId);
        $model->setChapterSubId($chapterSubId);
        $model->setType($type);
        $model->setStatus($status);
        $model->setLevel($level);
        $model->setNodeType($nodeType);
        $model->setYear($year);
        $model->setSource($source);
        $model->setTopic($topic);
        $this->db()->save($model);
        $sql = "SELECT a FROM Qa:TeachQANodeSub a WHERE a.id=:id ";
        $subModel= $this->db()->fetchOne($sql, ["id"=>$id], 1);
        $options = json_encode($choose);
        $subModel->setAnalysis($analysis);
        $subModel->setAnswer($answer);
        $subModel->setKnowledge($knowledge);
        $subModel->setOptions($options);
        $subModel->setScore($score);
        return $this->db()->save($subModel);
    }

    public function getById($id){
        $dql = "SELECT a FROM Qa:TeachQANode a WHERE a.id=:id";
        $info = $this->db()->fetchOne($dql, ["id"=>$id]);
        $subSql = "SELECT a FROM Qa:TeachQANodeSub a WHERE a.qaNodeId=:qaNodeId";
        $sub = $this->db()->fetchOne($subSql, ["qaNodeId"=>$id]);
        $sub['options'] = (isset($sub['options']) && $sub['options']) ?json_decode($sub['options'], true):[];
        $info['sub'] = $sub;
        return $info;
    }

    public function del($id){
        $sql = "SELECT a FROM Qa:TeachQANode a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $this->db()->delete($model);

        $sql = "SELECT a FROM Qa:TeachQANodeSub a WHERE a.qaNodeId=:qaNodeId";
        $model = $this->db()->fetchOne($sql, ['qaNodeId' => $id], 1);
        $this->db()->delete($model);

        return true;
    }

    public function switchStatus($id, $state){
        $sql = "SELECT a FROM Qa:TeachQANode a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->db()->save($model);
    }

}
