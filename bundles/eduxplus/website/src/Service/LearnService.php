<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/15 12:01
 */

namespace Eduxplus\WebsiteBundle\Service;


use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\EduxBundle\Entity\MallStudyLog;
use Eduxplus\EduxBundle\Entity\TeachCourseVideos;
use Knp\Component\Pager\PaginatorInterface;

class LearnService extends AppBaseService
{

    protected $paginator;
    protected $goodsService;

    public function __construct(PaginatorInterface $paginator, GoodsService $goodsService)
    {
        $this->paginator = $paginator;
        $this->goodsService = $goodsService;
    }

    public function getList($uid, $page, $pageSize)
    {
        $time = time();
        $dql = "SELECT a.courseId, abs({$time}-a.openTime) as diffTime FROM Edux:MallOrderStudyPlan a WHERE a.uid=:uid AND a.orderStatus=2 ORDER BY diffTime ASC";
        $em = $this->getDoctrine()->getManager();
        $em = $this->db()->enableSoftDeleteable($em);
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
                $courseId = $vArr['courseId'];
                $sql = "SELECT a FROM Edux:TeachCourse a WHERE a.id=:id";
                $itemsArr[] =  $this->db()->fetchOne($sql, ['id' => $courseId]);
            }
        }
        return [$pagination, $itemsArr];
    }

    public function getCourseCount($uid)
    {
        $dql = "SELECT count(a.id) as cnt FROM Edux:MallOrderStudyPlan a WHERE a.uid=:uid AND a.orderStatus=2 ";
        return $this->db()->fetchField("cnt", $dql, ['uid' => $uid]);
    }


    public function getCourseInfo($courseId)
    {
        $sql = "SELECT a FROM Edux:TeachCourse a WHERE a.id=:id";
        return $this->db()->fetchOne($sql, ['id' => $courseId]);
    }

    public function checkChapterCanView($chapterId, $uid){
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ["id" => $chapterId]);
        if($info['isFree']) return true;
        if(!$uid) return false;
        $orderPlanSql = "SELECT a FROM Edux:MallOrderStudyPlan a WHERE a.courseId = :courseId AND a.uid=:uid";
        $orderPlans = $this->db()->fetchAll($orderPlanSql, ["courseId" => $info['courseId'], "uid"=>$uid]);
        if(!$orderPlans) return false;
        foreach ($orderPlans as $v){
            $orderId = $v["orderId"];
            $orderSql = "SELECT a FROM Edux:MallOrder a WHERE a.id=:id AND a.orderStatus=2";
            $orderInfo = $this->db()->fetchOne($orderSql, ["id"=>$orderId]);
            if($orderInfo) return true;
        }
        return false;
    }

    /**
     * 添加学习记录
     * @param $chapterId
     * @param $uid
     */
    public function addStudyLog($chapterId, $uid){
        $logsql = "SELECT a.id FROM Edux:MallStudyLog a WHERE a.uid=:uid AND a.chapterId=:chapterId";
        $studyLog = $this->db()->fetchOne($logsql, ["uid"=>$uid, "chapterId"=>$chapterId]);
        if(!$studyLog){
            $model = new MallStudyLog();
            $model->setUid($uid);
            $model->setChapterId($chapterId);
            $this->db()->save($model);
        }
    }


    /**
     * 检查挡板
     * @param $courseId
     * @param $uid
     * @return bool
     */
    public function checkBlock($chapterId, $uid){
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ["id" => $chapterId]);
        $courseId = $info['courseId'];
        $orderPlanSql = "SELECT a FROM Edux:MallOrderStudyPlan a WHERE a.courseId = :courseId AND a.uid=:uid";
        $orderPlans = $this->db()->fetchAll($orderPlanSql, ["courseId" => $courseId, "uid"=>$uid]);
        if(!$orderPlans) return false;
        foreach ($orderPlans as $v){
            $studyPlanId = $v["studyPlanId"];
            $studyPlanSql = "SELECT a FROM Edux:TeachStudyPlan a WHERE a.id=:id";
            $studyPlan = $this->db()->fetchOne($studyPlanSql, ["id"=>$studyPlanId]);
            //挡板处理
            if($studyPlan['isBlock']){
                //如果已经存在则表示以前有权限
                $logsql = "SELECT a.id FROM Edux:MallStudyLog a WHERE a.uid=:uid AND a.chapterId=:chapterId";
                $studyLog = $this->db()->fetchOne($logsql, ["uid"=>$uid, "chapterId"=>$chapterId]);
                if($studyLog) return true;

                $studyPlanSubSql = "SELECT a FROM Edux:TeachStudyPlanSub a WHERE a.studyPlanId =:studyPlanId ORDER BY a.sort ASC";
                $studyPlanSubCourseIds = $this->db()->fetchFields("courseId", $studyPlanSubSql, ["studyPlanId"=>$studyPlanId]);
                $preCounseIds = [];
                foreach ($studyPlanSubCourseIds as $k=>$subCourseId){
                    $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.courseId = :courseId ORDER BY a.sort ASC";
                    $ids = $this->db()->fetchFields("id", $sql, ["courseId"=>$courseId]);
                    $firstId = isset($ids[0])?$ids[0]:0;
                    $endId = $ids[count($ids)-1];
                    if($k > 0){
                        //检查上一个课程最后章节是否看过
                        $preCounseEndId = $preCounseIds[count($preCounseIds)-1];
                        $logsql = "SELECT a.id FROM Edux:MallStudyLog a WHERE a.uid=:uid AND a.chapterId=:chapterId";
                        $studyLog = $this->db()->fetchOne($logsql, ["uid"=>$uid, "chapterId"=>$preCounseEndId]);
                        if(!$studyLog) return false;
                    }
                    //课程第一个章节
                    if($firstId == $chapterId)  return true;
                    $preChapterId = 0;
                    foreach ($ids as $chapterIdDiff){
                        if($chapterIdDiff == $chapterId){//找到自己的位置,判断上一章节是否看过
                            $logsql = "SELECT a.id FROM Edux:MallStudyLog a WHERE a.uid=:uid AND a.chapterId=:chapterId";
                            $studyLog = $this->db()->fetchOne($logsql, ["uid"=>$uid, "chapterId"=>$preChapterId]);
                            if($studyLog) return true;
                        }
                        $preChapterId = $chapterIdDiff;
                    }

                    $preCounseIds = $ids;
                }
            }
        }
        return true;
    }

    public function getChapter($chapterId)
    {
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.id=:id";
        $info = $this->db()->fetchOne($sql, ["id" => $chapterId]);
        if (!$info) return [];
        $info['isOpen'] = 0;
        if($info['openTime']<time()){
            $info['isOpen'] = 1;
        }
        $info['teachers'] = $this->getTeacherIds($chapterId);
        $info['video'] = $this->getVideoById($chapterId);
        $info['materials'] = $this->getMaterialsById($chapterId);
        return $info;
    }

    public function getChapterTree($courseId, $getActiveVideo = 0)
    {
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.courseId=:courseId ORDER BY a.sort ASC";
        $list = $this->db()->fetchAll($sql, ["courseId" => $courseId]);
        if (!$list) return [];

        $rs = [];
        $pathCount = 0;
        foreach ($list as $v) {
            $path = trim($v['path'], ",");
            if($path){
                $pathArr = explode(",", $path);
                $currentPathCount = count($pathArr);
            }else{
                $currentPathCount = 0;
            }

            $pid = $v['parentId'];
            $chapterId = $v['id'];
            $v['teachers'] = $this->getTeacherIds($chapterId);

            if ($getActiveVideo) {
                $v['video'] = $this->getActiveVideoById($chapterId);
            } else {
                $v['video'] = $this->getVideoById($chapterId);
            }

            $v['materials'] = $this->getMaterialsById($chapterId);
            $rs[$pid][] = $v;
            $pathCount = $currentPathCount > $pathCount ? $currentPathCount : $pathCount;
        }

        return [$rs, $pathCount];
    }


    public function getTeacherIds($chapterId)
    {
        $sql = "SELECT a FROM Edux:TeachCourseTeachers a WHERE a.chapterId=:chapterId";
        $teacherIds = $this->db()->fetchFields('teacherId', $sql, ['chapterId' => $chapterId]);
        if (!$teacherIds) return [];
        $sql = "SELECT a FROM Edux:JwTeacher a WHERE a.id IN(:id)";
        return $this->db()->fetchAll($sql, ['id' => $teacherIds]);
    }


    public function getVideoByRealId($id)
    {
        $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.id=:id";
        return $this->db()->fetchOne($sql, ['id' => $id]);
    }

    public function getVideoById($id)
    {
        $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.chapterId=:chapterId ";
        return $this->db()->fetchOne($sql, ['chapterId' => $id]);
    }

    public function getActiveVideoById($id)
    {
        $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.chapterId=:chapterId AND a.status=:status ";
        return $this->db()->fetchOne($sql, ['chapterId' => $id, "status" => 1]);
    }

    public function getMaterialsById($id)
    {
        $sql = "SELECT a FROM Edux:TeachCourseMaterials a WHERE a.chapterId=:chapterId";
        return $this->db()->fetchOne($sql, ['chapterId' => $id]);
    }

    /**
     * 更新视频状态
     *
     * @param $videoId
     */
    public function updateVideoStatus($videoChannel, $videoId, $status = 1)
    {
        $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.videoId=:videoId AND a.videoChannel=:videoChannel";
        $model = $this->db()->fetchOne($sql, ['videoId' => $videoId, "videoChannel" => $videoChannel], 1);
        if ($model) {
            $model->setStatus($status);
            $this->db()->save($model);
        }
    }

    function modifyCoverImg($coverImg, $type, $videoChannel, $videoId)
    {
        if(!$coverImg) return ;

        if ($type == 2) {
            if ($videoChannel == 2) { //阿里云

            } else if ($videoChannel == 1) { //腾讯云
                $img = $this->goodsService->baseCurlGet($coverImg, "get");
                if($img){
                    $coverImgData = base64_encode($img);
                    $this->tengxunyunVodService->ModifyMediaInfo($videoId, $coverImgData);
                }
            }
        }
    }

    public function getVideoByVideoId($videoId)
    {
        $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.videoId=:videoId";
        return $this->db()->fetchOne($sql, ['videoId' => $videoId]);
    }

    /**
     * 获取直播数据
     * @param $liveData
     */
    public function parseLiveData($liveData, $liveAdapter=0){
        if(!$liveData) return [];
        if(!$liveAdapter) $liveAdapter = $this->getOption("app.live.adapter");
        if($liveAdapter == 1){
            $key = "tengxunyunLive";
        }else if($liveAdapter == 2){
            $key = "aliyunLive";
        }
        $liveDataArr = json_decode($liveData, true);
        if(!isset($liveDataArr[$key])) return [];
        $pushUrl = isset($liveDataArr[$key]['pushUrl'])?$liveDataArr[$key]['pushUrl']:"";
        $playUrl = isset($liveDataArr[$key]['playUrl'])?$liveDataArr[$key]['playUrl']:"";
        return ["playUrl"=>$playUrl, "pushUrl"=>$pushUrl];
    }

}
