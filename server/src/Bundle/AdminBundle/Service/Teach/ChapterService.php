<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 20:19
 */

namespace App\Bundle\AdminBundle\Service\Teach;


use App\Bundle\AdminBundle\Service\Jw\TeacherService;
use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Entity\TeachCourseChapter;
use App\Entity\TeachCourseTeachers;
use App\Entity\TeachCourseVideos;

class ChapterService extends BaseService
{

    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function getChapterTree($parentId)
    {
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.parentId = :parentId ORDER BY a.sort ASC";
        $items = $this->fetchAll($sql, ['parentId'=>$parentId]);
        if(!$items) return [];
        $result = [];
        foreach ($items as &$v){
            $tmp=$v;
            $child =  $this->getChapterTree($v['id']);
            $tmp['childs'] = $child;
            $result[] = $tmp;
        }
        return $result;
    }


    public function updateSort($data, $pid=0){
        if($data){
            $sort = 0;
            foreach ($data as $k=>$v){
                $id = $v['id'];
                $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.id=:id";
                $model = $this->fetchOne($sql, ['id'=>$id], 1);
                $model->setSort($sort);
                $model->setParentId($pid);
                $this->update($model);
                if(isset($v['children'])){
                    $this->updateSort($v['children'], $id);
                }
                $sort++;
            }
        }
    }

    public function getAllChapter(){
        $sql = "SELECT a FROM App:TeachCourseChapter a ORDER BY a.sort ASC";
        $list = $this->fetchAll($sql);
        if(!$list) return [];

        $rs = [];
        foreach ($list as $v){
            $pid = $v['parentId'];
            $rs[$pid][] = $v;
        }
        return $rs;
    }

    public function chapterSelect(){
        $all = $this->getAllChapter();
        $rs = [];
        $rs['root'] = 0;
        if($all){
            foreach ($all[0] as $vv){
                $id= $vv['id'];
                $name = "┝&nbsp;".$vv['name'];
                $rs[$name] = $id;
                if(isset($all[$id])){
                    $pre = "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_Select($all,$id, $pre, $rs);
                }
            }
        }
        return $rs;
    }

    protected function _Select($all, $id, $pre="", &$rs){
        if($all[$id]){
            foreach ($all[$id] as $v){
                $id= $v['id'];
                $name = $pre."┝&nbsp;".$v['name'];
                $rs[$name] = $id;
                if(isset($all[$id])){
                    $pre = $pre."&nbsp;&nbsp;&nbsp;&nbsp;";
                    $this->_Select($all, $id, $pre, $rs);
                }
            }
        }
    }

    public function getTeachers(){
        $allTeachers = $this->teacherService->getAll();
        if(!$allTeachers) return [];
        $rs = [];
        foreach ($allTeachers as $v){
            $rs[$v['name']] = $v['id'];
        }
        return $rs;
    }

    public function add($name, $teachers, $parentId, $openTime, $studyWay, $isFree, $sort, $id){
        $model = new TeachCourseChapter();
        $model->setName($name);
        $model->setParentId($parentId);
        if($openTime) $model->setOpenTime($openTime);
        $model->setStudyWay($studyWay);
        $model->setIsFree($isFree);
        $model->setSort($sort);
        $model->setCourseId($id);
        $chapterId = $this->save($model);

        if($teachers){
            foreach ($teachers as $teacherId){
                $tmodel = new TeachCourseTeachers();
                $tmodel->setCourseId($id);
                $tmodel->setChapterId($chapterId);
                $tmodel->setTeacherId($teacherId);
                $this->save($tmodel);
            }
        }
        return $chapterId;
    }

    public function edit($id , $name, $teachers, $parentId, $openTime, $studyWay, $isFree, $sort){
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id], 1);
        $courseId = $model->getCourseId();

        $model->setName($name);
        $model->setParentId($parentId);
        if($openTime) $model->setOpenTime($openTime);
        $model->setStudyWay($studyWay);
        $model->setIsFree($isFree);
        $model->setSort($sort);
        $this->save($model);
        if($teachers){
            $sql2 = "SELECT a FROM App:TeachCourseTeachers a WHERE a.chapterId=:chapterId";
            $tmodels = $this->fetchAll($sql2, ["chapterId"=>$id], 1);
            $this->delete($tmodels);
            foreach ($teachers as $teacherId){
                $tmodel = new TeachCourseTeachers();
                $tmodel->setCourseId($courseId);
                $tmodel->setChapterId($id);
                $tmodel->setTeacherId($teacherId);
                $this->save($tmodel);
            }
        }
    }

    public function getById($id){
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.id=:id";
        return $this->fetchOne($sql, ['id'=>$id]);
    }

    public function getTeacherIds($chapterId){
        $sql = "SELECT a FROM App:TeachCourseTeachers a WHERE a.chapterId=:chapterId";
        return $this->fetchFields('teacherId', $sql, ['chapterId'=>$chapterId]);
    }

    public function del($id){
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.id=:id";
        $model = $this->fetchOne($sql, ['id'=>$id] ,1 );
        return $this->delete($model);
    }

    public function hasChild($id){
        $sql = "SELECT a FROM App:TeachCourseChapter a WHERE a.parentId=:parentId";
        return $this->fetchOne($sql, ['parentId'=>$id]);
    }

    public function getVideoById($id){
        $sql = "SELECT a FROM App:TeachCourseVideos a WHERE a.chapterId=:chapterId";
        return $this->fetchOne($sql, ['chapterId'=>$id]);
    }

    /**
     * 创建点播
     */
    public function addVideos($chapterId, $type, $videoChannel, $channelData){
        $chapterInfo = $this->getById($chapterId);
        $courseId = $chapterInfo['courseId'];
        $sql = "SELECT a FROM App:TeachCourseVideos a WHERE a.chapterId=:chapterId";
        $model = $this->fetchOne($sql, ["chapterId"=>$chapterId], 1);
        if(!$model){
            $model = new TeachCourseVideos();
        }
        $model->setChapterId($chapterId);
        $model->setCourseId($courseId);
        $model->setType($type);
        $model->setStatus(1);
        $model->setChannelData($channelData);
        $model->setVideoChannel($videoChannel);

        return $this->save($model);
    }

}
