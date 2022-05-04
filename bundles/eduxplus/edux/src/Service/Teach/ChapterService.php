<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 20:19
 */

namespace Eduxplus\EduxBundle\Service\Teach;


use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\EduxBundle\Service\Jw\TeacherService;
use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Service\Base\Live\AliyunLiveService;
use Eduxplus\CoreBundle\Lib\Service\Base\Live\TengxunyunLiveService;
use Eduxplus\CoreBundle\Lib\Service\Base\Vod\AliyunVodService;
use Eduxplus\CoreBundle\Lib\Service\Base\Vod\TengxunyunVodService;
use Eduxplus\EduxBundle\Entity\TeachCourseChapter;
use Eduxplus\EduxBundle\Entity\TeachCourseMaterials;
use Eduxplus\EduxBundle\Entity\TeachCourseTeachers;
use Eduxplus\EduxBundle\Entity\TeachCourseVideos;

class ChapterService extends AdminBaseService
{

    protected $teacherService;
    protected $aliyunVodService;
    protected $tengxunyunVodService;
    protected $tengxunyunLiveService;
    protected $aliyunLiveService;

    public function __construct(TeacherService $teacherService,
                                AliyunVodService $aliyunVodService,
                                TengxunyunVodService $tengxunyunVodService,
                                TengxunyunLiveService $tengxunyunLiveService,
                                AliyunLiveService $aliyunLiveService
)
    {
        $this->teacherService = $teacherService;
        $this->aliyunVodService = $aliyunVodService;
        $this->tengxunyunVodService = $tengxunyunVodService;
        $this->tengxunyunLiveService = $tengxunyunLiveService;
        $this->aliyunLiveService = $aliyunLiveService;
    }

    public function getChapterTree($parentId, $courseId)
    {
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.parentId = :parentId AND a.courseId = :courseId ORDER BY a.sort ASC";
        $items = $this->db()->fetchAll($sql, ['parentId' => $parentId, "courseId" => $courseId]);
        if (!$items) return [];
        $result = [];
        foreach ($items as &$v) {
            $tmp = $v;
            $child =  $this->getChapterTree($v['id'], $courseId);
            $tmp['childs'] = $child;
            $tmp['video'] = $this->getVideoById($v['id']);
            $result[] = $tmp;
        }
        return $result;
    }


    public function updateSort($data, $pid = 0)
    {
        if ($data) {
            $sort = 0;
            foreach ($data as $k => $v) {
                $id = $v['id'];
                $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.id=:id";
                $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
                $model->setSort($sort);
                $model->setParentId($pid);
                $this->db()->save($model);
                $sort++;
                if (isset($v['children'])) {
                    $this->updateSort($v['children'], $id);
                }
            }
        }
    }

    public function getAllChapter($courseId)
    {
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.courseId=:courseId ORDER BY a.sort ASC";
        $list = $this->db()->fetchAll($sql, ["courseId" => $courseId]);
        if (!$list) return [];

        $rs = [];
        foreach ($list as $v) {
            $pid = $v['parentId'];
            $rs[$pid][] = $v;
        }
        return $rs;
    }

    public function chapterSelect($courseId)
    {
        $all = $this->getAllChapter($courseId);
        $rs = [];
        $rs['/'] = 0;
        if ($all) {
            foreach ($all[0] as $vv) {
                $id = $vv['id'];
                $name = "┝&nbsp;" . $vv['name'];
                $rs[$name] = $id;
                if (isset($all[$id])) {
                    $pre = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_Select($all, $id, $pre, $rs);
                }
            }
        }
        return $rs;
    }

    protected function _Select($all, $id, $pre, &$rs)
    {
        if ($all[$id]) {
            foreach ($all[$id] as $v) {
                $id = $v['id'];
                $name = $pre . "┝&nbsp;" . $v['name'];
                $rs[$name] = $id;
                if (isset($all[$id])) {
                    $pre = $pre . "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_Select($all, $id, $pre, $rs);
                }
            }
        }
    }

    public function getTeachers()
    {
        $allTeachers = $this->teacherService->getAll();
        if (!$allTeachers) return [];
        $rs = [];
        foreach ($allTeachers as $v) {
            $rs[$v['name']] = $v['id'];
        }
        return $rs;
    }

    public function add($name, $teachers, $parentId, $openTime, $studyWay, $isFree, $sort, $id)
    {
        $path = $this->findPath($parentId);
        $model = new TeachCourseChapter();
        $model->setName($name);
        $model->setParentId($parentId);
        if ($openTime) $model->setOpenTime($openTime);
        $model->setStudyWay($studyWay);
        $model->setIsFree($isFree);
        $model->setSort($sort);
        $model->setCourseId($id);
        $model->setPath($path);
        $chapterId = $this->db()->save($model);

        if ($teachers) {
            foreach ($teachers as $teacherId) {
                $tmodel = new TeachCourseTeachers();
                $tmodel->setCourseId($id);
                $tmodel->setChapterId($chapterId);
                $tmodel->setTeacherId($teacherId);
                $this->db()->save($tmodel);
            }
        }

        if ($openTime) {
            //对比开课时间设置最早开课时间
            $this->updateCourseOpenTime($id, $openTime);
        }

        return $chapterId;
    }

    public function findPath($id)
    {
        if (!$id) return ",";
        $sql = "SELECT a.parentId FROM Edux:TeachCourseChapter a WHERE a.id = :id";
        $pid = $this->db()->fetchField("parentId", $sql, ['id' => $id]);
        if (!$pid) return ",{$id},";
        $str = ",{$id},";
        $str .= ltrim($this->findPath($pid), ",");
        return $str;
    }


    protected function updateCourseOpenTime($courseId, $openTime)
    {
        $sql = "SELECT a FROM Edux:TeachCourse a WHERE a.id=:id";
        $courseModel =  $this->db()->fetchOne($sql, ['id' => $courseId], 1);
        if ($courseModel) {
            $currentOpenTime = (int) $courseModel->getOpenTime();
            if ($currentOpenTime > $openTime) {
                $courseModel->setOpenTime($openTime);
                $this->db()->save($courseModel);
            }
        }
    }

    public function edit($id, $name, $teachers, $parentId, $openTime, $studyWay, $isFree, $sort)
    {
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        $courseId = $model->getCourseId();

        $path = $this->findPath($parentId);

        $model->setPath($path);
        $model->setName($name);
        $model->setParentId($parentId);
        if ($openTime) $model->setOpenTime($openTime);
        $model->setStudyWay($studyWay);
        $model->setIsFree($isFree);
        $model->setSort($sort);
        $this->db()->save($model);
        if ($teachers) {
            $sql2 = "SELECT a FROM Edux:TeachCourseTeachers a WHERE a.chapterId=:chapterId";
            $tmodels = $this->db()->fetchAll($sql2, ["chapterId" => $id], 1);
            $this->db()->delete($tmodels);
            foreach ($teachers as $teacherId) {
                $tmodel = new TeachCourseTeachers();
                $tmodel->setCourseId($courseId);
                $tmodel->setChapterId($id);
                $tmodel->setTeacherId($teacherId);
                $this->db()->save($tmodel);
            }
        }

        if ($openTime) {
            //对比开课时间设置最早开课时间
            $this->updateCourseOpenTime($courseId, $openTime);
        }
    }

    public function getById($id)
    {
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.id=:id";
        return $this->db()->fetchOne($sql, ['id' => $id]);
    }

    public function getCourseInfo($courseId)
    {
        $sql = "SELECT a FROM Edux:TeachCourse a WHERE a.id=:id";
        return $this->db()->fetchOne($sql, ['id' => $courseId]);
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


    public function getTeacherIds($chapterId)
    {
        $sql = "SELECT a FROM Edux:TeachCourseTeachers a WHERE a.chapterId=:chapterId";
        return $this->db()->fetchFields('teacherId', $sql, ['chapterId' => $chapterId]);
    }

    public function del($id)
    {
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.id=:id";
        $model = $this->db()->fetchOne($sql, ['id' => $id], 1);
        return $this->db()->delete($model);
    }

    public function hasChild($id)
    {
        $sql = "SELECT a FROM Edux:TeachCourseChapter a WHERE a.parentId=:parentId";
        return $this->db()->fetchOne($sql, ['parentId' => $id]);
    }

    public function getVideoById($id)
    {
        $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.chapterId=:chapterId";
        return $this->db()->fetchOne($sql, ['chapterId' => $id]);
    }

    public function getVideoByVideoId($videoId)
    {
        $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.videoId=:videoId";
        return $this->db()->fetchOne($sql, ['videoId' => $videoId]);
    }

    public function getMaterialsById($id)
    {
        $sql = "SELECT a FROM Edux:TeachCourseMaterials a WHERE a.chapterId=:chapterId";
        return $this->db()->fetchOne($sql, ['chapterId' => $id]);
    }

    public function getVideoName($chapterId)
    {
        $chapterInfo = $this->getById($chapterId);
        $chapterName = $chapterInfo['name'];
        $path = trim($chapterInfo['path'], ',');
        $pathIds = $path ? "-" . str_replace(",", "-", $path) : "";
        $courseId = $chapterInfo['courseId'];
        $sql = "SELECT a FROM Edux:TeachCourse a WHERE a.id=:id";
        $courseInfo = $this->db()->fetchOne($sql, ["id" => $courseId]);
        $courseName = $courseInfo['name'];
        $nameStr = $courseName . "@{$courseId}" . $pathIds . "-" . $chapterName . "@{$chapterId}";
        return $nameStr;
    }


    /**
     * 创建点播
     */
    public function addVideos($chapterId, $type, $videoChannel, $videoId)
    {
        try {
            $this->db()->beginTransaction();
            $chapterInfo = $this->getById($chapterId);
            $courseId = $chapterInfo['courseId'];
            $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.chapterId=:chapterId";
            $model = $this->db()->fetchOne($sql, ["chapterId" => $chapterId], 1);
            if (!$model) {
                $model = new TeachCourseVideos();
            }
            $model->setChapterId($chapterId);
            $model->setCourseId($courseId);
            $model->setType($type);
            $model->setStatus(0);
            $model->setVideoId($videoId);
            $model->setVideoChannel($videoChannel);
            $id = $this->db()->save($model);
            // throw new \Exception("aaa");

            $this->ayncTranscode($type, $videoChannel, $videoId);

            $this->db()->commit();
            return $id;
        } catch (\Exception $e) {
            $this->db()->rollback();
            return $this->error()->add($e->getMessage());
        }
    }

    /**
     * 异步启动转码
     * @param $type
     * @param $videoChannel
     * @param $channelData
     */
    public function ayncTranscode($type, $videoChannel, $videoId)
    {

        if ($type == 2) {
            if ($videoChannel == 2) {
                $this->aliyunVodService->submitTranscodeJobs($videoId, function($videoId, $dataKey){
                    $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.videoId=:videoId";
                    $model = $this->db()->fetchOne($sql, ["videoId"=>$videoId], 1);
                    $vodData = $model->getVodData();
                    $vodData = $vodData?json_decode($vodData, true):[];
                    $vodData['aliyunVod'] = $dataKey;
                    $model->setVodData(json_encode($vodData));
                    $this->db()->save($model);
                });
            } else if ($videoChannel == 1) {
                $this->tengxunyunVodService->processMediaByProcedureRequest($videoId);
            }
        }
    }

    public function addMaterials($chapterId, $path)
    {
        $chapterInfo = $this->getById($chapterId);
        $courseId = $chapterInfo['courseId'];
        $sql = "SELECT a FROM Edux:TeachCourseMaterials a WHERE a.chapterId=:chapterId";
        $model = $this->db()->fetchOne($sql, ["chapterId" => $chapterId], 1);
        if (!$model) {
            $model = new TeachCourseMaterials();
        }
        $model->setChapterId($chapterId);
        $model->setCourseId($courseId);
        $model->setPath($path);
        return $this->db()->save($model);
    }

    /**
     * 获取视频上传地域
     *
     * @return mixed|null|string
     */
    public function getRegion()
    {
        $vodAdapter = $this->getOption("app.vod.adapter");
        if ($vodAdapter == 1) {
            return $this->tengxunyunVodService->getRegion();
        } else if ($vodAdapter == 2) {
            return $this->aliyunVodService->getRegion();
        }
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

    /**
     * 设置解析
     * @param $pushUrl
     * @param $playUrl
     * @param string $liveData
     * @return mixed
     */
    public function parseSetLiveData($pushUrl,  $playUrl, $liveData="", $liveAdapter=0){
        if(!$liveAdapter) $liveAdapter = $this->getOption("app.live.adapter");
        if($liveAdapter == 1){
            $key = "tengxunyunLive";
        }else if($liveAdapter == 2){
            $key = "aliyunLive";
        }

        if($liveData){
            $liveDataArr = json_decode($liveData, true);
            $liveDataArr[$key]=["playUrl"=>$playUrl, "pushUrl"=>$pushUrl];
        }else{
            $liveDataArr = [];
            $liveDataArr[$key]=["playUrl"=>$playUrl, "pushUrl"=>$pushUrl];
        }
        return json_encode($liveDataArr);
    }

    /**
     * 生成直播数据
     * @param $chapterId
     */
    public function genLiveData($chapterId){
        //获取开课时间
        $chapterInfo = $this->getById($chapterId);
        if(!$chapterInfo) return $this->error()->add("章节不存在!");
        $openTime = $chapterInfo["openTime"];
        if(!$openTime) return $this->error()->add("开课时间不能为空!");
        if($openTime<time()) return $this->error()->add("开课时间已过期!");
        $expireTime = $openTime+86400*3;//开课时间三天后过期
        $videoInfo = $this->getVideoById($chapterId);
        $liveAdapter = $this->getOption("app.live.adapter");
        if($videoInfo){
            $liveAdapter = $videoInfo['videoChannel'];
        }
        $oldLiveData = isset($videoInfo['liveData'])?$videoInfo['liveData']:"";
        $streamName = "chapter".$chapterId;
        $parseSetLiveData = "";

        if($liveAdapter == 1){
            $pushUrl = $this->tengxunyunLiveService->createPushUrl($streamName, $expireTime);
            $playUrl = $this->tengxunyunLiveService->createPlayUrl($streamName, $expireTime);
            $parseSetLiveData = $this->parseSetLiveData($pushUrl,  $playUrl, $oldLiveData, $liveAdapter);
        }else if($liveAdapter == 2){
            $appName = $this->getOption("app.aliyun.live.appName");
            $pushUrl = $this->aliyunLiveService->createPushUrl($appName,$streamName, $expireTime);
            $playUrl = $this->aliyunLiveService->createPlayUrl($appName,$streamName, $expireTime);
            $parseSetLiveData = $this->parseSetLiveData($pushUrl,  $playUrl, $oldLiveData, $liveAdapter);
        }
        $sql = "SELECT a FROM Edux:TeachCourseVideos a WHERE a.chapterId=:chapterId";
        $model = $this->db()->fetchOne($sql, ["chapterId" => $chapterId], 1);
        if (!$model) {
            $model = new TeachCourseVideos();
        }
//        var_dump($parseSetLiveData);
        $model->setLiveData($parseSetLiveData);
        $model->setChapterId($chapterId);
        $model->setCourseId($chapterInfo['courseId']);
        $model->setType(1);
        $model->setStatus(0);
        $model->setVideoChannel($liveAdapter);
        $id = $this->db()->save($model);
        return $id;
    }
}
