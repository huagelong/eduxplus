<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/12/1 19:23
 */

namespace App\Bundle\QABundle\Service\Admin;


use App\Bundle\AdminBundle\Service\UserService;
use App\Bundle\AppBundle\Lib\Base\AdminBaseService;
use App\Bundle\QABundle\Entity\TeachQANode;
use App\Bundle\QABundle\Entity\TeachQANodeSub;
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

        $dql = "SELECT a FROM QA:TeachQANode a " . $sql . " ORDER BY a.id DESC";
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
                $sql = "SELECT a FROM QA:TeachQANodeSub a WHERE a.qaNodeId=:qaNodeId";
                $nodeSub = $this->fetchOne($sql, ['qaNodeId' => $vArr['id']]);
                $vArr['nodeSub'] = $nodeSub;

                //章节点
                $chapterSubSql = "SELECT a FROM QA:TeachQAChapterSub a WHERE a.id=:id";
                $chapterSubName = $this->fetchField("name",$chapterSubSql, ["id"=>$vArr['chapterSubId']]);
                $vArr['chapterSubName'] = $chapterSubName;

                $createrUid = $vArr['createUid'];
                $createrUser = $this->userService->getById($createrUid);
                $vArr['creater'] = $createrUser['fullName'];

                $itemsArr[] = $vArr;
            }
        }

        return [$pagination, $itemsArr];
    }

    public function add($choose,$answer,$uid,$chapterId, $chapterSubId,$type, $status, $level, $nodeType, $year, $knowledge, $source,$topic,$analysis){
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
            $nodeId = $this->save($model);
            if(!$nodeId) return $this->error()->add("添加失败!");
            $options = json_encode($choose);
            $subModel = new TeachQANodeSub();
            $subModel->setAnalysis($analysis);
            $subModel->setAnswer($answer);
            $subModel->setKnowledge($knowledge);
            $subModel->setQaNodeId($nodeId);
            $subModel->setOptions($options);
            $this->save($subModel);
            return $nodeId;
    }

    public function edit($id, $choose,$answer,$chapterId, $chapterSubId,$type, $status, $level, $nodeType, $year, $knowledge, $source,$topic,$analysis){
        $sql = "SELECT a FROM QA:TeachQANode a WHERE a.id=:id ";
        $model= $this->fetchOne($sql, ["id"=>$id], 1);
        $model->setChapterId($chapterId);
        $model->setChapterSubId($chapterSubId);
        $model->setType($type);
        $model->setStatus($status);
        $model->setLevel($level);
        $model->setNodeType($nodeType);
        $model->setYear($year);
        $model->setSource($source);
        $model->setTopic($topic);
        $this->save($model);
        $sql = "SELECT a FROM QA:TeachQANodeSub a WHERE a.id=:id ";
        $subModel= $this->fetchOne($sql, ["id"=>$id], 1);
        $options = json_encode($choose);
        $subModel->setAnalysis($analysis);
        $subModel->setAnswer($answer);
        $subModel->setKnowledge($knowledge);
        $subModel->setOptions($options);
        return $this->save($subModel);
    }

    public function getById($id){
        $dql = "SELECT a FROM QA:TeachQANode a WHERE a.id=:id";
        $info = $this->fetchOne($dql, ["id"=>$id]);
        $subSql = "SELECT a FROM QA:TeachQANodeSub a WHERE a.qaNodeId=:qaNodeId";
        $sub = $this->fetchOne($subSql, ["qaNodeId"=>$id]);
        $sub['options'] = (isset($sub['options']) && $sub['options']) ?json_decode($sub['options'], true):[];
        $info['sub'] = $sub;
        return $info;
    }

    public function del($id){
        $sql = "SELECT a FROM QA:TeachQANode a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $this->delete($model);

        $sql = "SELECT a FROM QA:TeachQANodeSub a WHERE a.qaNodeId=:qaNodeId";
        $model = $this->fetchOne($sql, ['qaNodeId' => $id], 1);
        $this->delete($model);

        return true;
    }

    public function switchStatus($id, $state){
        $sql = "SELECT a FROM QA:TeachQANode a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id' => $id], 1);
        $model->setStatus($state);
        return $this->save($model);
    }

}
