<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 19:53
 */

namespace App\Bundle\AdminBundle\Controller\Teach;

use App\Bundle\AdminBundle\Service\Teach\ChapterService;
use App\Bundle\AdminBundle\Service\Teach\CourseService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use App\Bundle\AppBundle\Lib\Service\Live\AliyunLiveService;
use App\Bundle\AppBundle\Lib\Service\Live\TengxunyunLiveService;
use App\Bundle\AppBundle\Lib\Service\Vod\AliyunVodService;
use App\Bundle\AppBundle\Lib\Service\Vod\TengxunyunVodService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class ChapterController extends BaseAdminController
{

    /**
     *
     * @Rest\Get("/teach/chapter/index/{id}", name="admin_teach_chapter_index")
     */
    public function indexAction($id, ChapterService $chapterService)
    {
        $data = [];
        $data['all'] = $chapterService->getChapterTree(0, $id);
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/chapter/index.html.twig", $data);
    }

    /**
     *
     * @Rest\Get("/teach/chapter/add/{id}", name="admin_teach_chapter_add")
     */
    public function addAction($id, Form $form, Request $request, ChapterService $chapterService, CourseService $courseService)
    {
        $select = $chapterService->chapterSelect($id);

        $parentId = $request->get("pid");
        $form->text("名称")->field("name")->isRequire(1);
        $form->select("父章节")->field("parentId")->isRequire(1)->defaultValue($parentId)->options($select);
        $form->datetime("上课时间")->field("openTime")->placeholder("直播必须输入上课时间");
        $form->multiSelect("上课老师")->field("teachers[]")->isRequire(1)->options($chapterService->getTeachers());

        $courseInfo = $courseService->getById($id);
        $teachType = $courseInfo['type'];
        $form->select("学习方式")->field("studyWay")->isRequire(1)->options(function () use ($teachType) {
            if ($teachType == 1) {
                return [
                    "直播" => 1,
                    "点播" => 2
                ];
            } else if ($teachType == 2) {
                return [
                    "面授" => 3
                ];
            } else if ($teachType == 3) {
                return [
                    "直播" => 1,
                    "点播" => 2,
                    "面授" => 3
                ];
            }
        });
        $form->boole("免费？")->field("isFree")->isRequire(1);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue(0);

        $formData = $form->create($this->generateUrl("admin_api_teach_chapter_add", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/chapter/add.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/teach/chapter/add/do/{id}", name="admin_api_teach_chapter_add")
     */
    public function addDoAction($id, Request $request, ChapterService $chapterService)
    {
        $name = $request->get("name");
        $parentId = (int) $request->get("parentId");
        $openTime = $request->get("openTime");
        $studyWay = (int) $request->get("studyWay");
        $isFree = $request->get("isFree");
        $sort = (int) $request->get("sort");
        $teachers = $request->get("teachers");

        $isFree = $isFree == "on" ? 1 : 0;

        if (!$name)
            return $this->responseError("章节名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50)
            return $this->responseError("章节名称不能大于50字!");

        if (!$teachers)
            return $this->responseError("上课老师不能为空!");

        $path = $chapterService->findPath($parentId);
        $path = trim($path, ",");
        if($path){
            $pathArr = explode(",", $path);
            $currentPathCount = count($pathArr);
        }else{
            $currentPathCount = 0;
        }
        if($currentPathCount>1) return $this->responseError("章节层级不能达到3级!");

        $openTime = $openTime ? strtotime($openTime) : 0;
        if($studyWay == 1){
            if(!$openTime) return $this->responseError("直播时，上课时间不能为空!");
        }

        $chapterService->add($name, $teachers, $parentId, $openTime, $studyWay, $isFree, $sort, $id);

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_teach_chapter_index', [
            'id' => $id
        ]));
    }

    /**
     *
     * @Rest\Get("/teach/chapter/edit/{id}", name="admin_teach_chapter_edit")
     */
    public function editAction($id, Form $form, ChapterService $chapterService, CourseService $courseService)
    {
        $info = $chapterService->getById($id);

        $select = $chapterService->chapterSelect($info["courseId"]);

        $form->text("名称")->field("name")->isRequire(1)->defaultValue($info['name']);
        $form->select("父章节")->field("parentId")->isRequire(1)->defaultValue($info['parentId'])->options($select);
        $form->datetime("上课时间")->field("openTime")->defaultValue($info['openTime']?date('Y-m-d H:i', $info['openTime']):"")->placeholder("直播必须输入上课时间");

        $teacherIds = $chapterService->getTeacherIds($id);
        $form->multiSelect("上课老师")->field("teachers[]")->isRequire(1)->defaultValue($teacherIds)->options($chapterService->getTeachers());

        $courseInfo = $courseService->getById($info["courseId"]);
        $teachType = $courseInfo['type'];
        $form->select("学习方式")->field("studyWay")->isRequire(1)->defaultValue($info['studyWay'])->options(function () use ($teachType) {
            if ($teachType == 1) {
                return [
                    "直播" => 1,
                    "点播" => 2
                ];
            } else if ($teachType == 2) {
                return [
                    "面授" => 3
                ];
            } else if ($teachType == 3) {
                return [
                    "直播" => 1,
                    "点播" => 2,
                    "面授" => 3
                ];
            }
        });
        $form->boole("免费？")->field("isFree")->isRequire(1)->defaultValue($info['isFree']);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue(0)->defaultValue($info['sort']);

        $formData = $form->create($this->generateUrl("admin_api_teach_chapter_edit", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/chapter/edit.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/teach/chapter/edit/do/{id}", name="admin_api_teach_chapter_edit")
     */
    public function editDoAction($id, Request $request, ChapterService $chapterService)
    {
        $name = $request->get("name");
        $parentId = (int) $request->get("parentId");
        $openTime = $request->get("openTime");
        $studyWay = (int) $request->get("studyWay");
        $isFree = $request->get("isFree");
        $sort = (int) $request->get("sort");
        $teachers = $request->get("teachers");
        $isFree = $isFree == "on" ? 1 : 0;

        if (!$name)
            return $this->responseError("章节名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50)
            return $this->responseError("章节名称不能大于50字!");

        if (!$teachers)
            return $this->responseError("上课老师不能为空!");

        $path = $chapterService->findPath($parentId);
        $path = trim($path, ",");
        if($path){
            $pathArr = explode(",", $path);
            $currentPathCount = count($pathArr);
        }else{
            $currentPathCount =0;
        }

        if($currentPathCount>1) return $this->responseError("章节层级不能达到3级!");

        $openTime = $openTime ? strtotime($openTime) : 0;

        if($studyWay == 1){
            if(!$openTime) return $this->responseError("直播时，上课时间不能为空!");
        }

        $chapterService->edit($id, $name, $teachers, $parentId, $openTime, $studyWay, $isFree, $sort);

        $info = $chapterService->getById($id);

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_teach_chapter_index', [
            'id' => $info['courseId']
        ]));
    }

    /**
     *
     * @Rest\Post("/teach/chapter/delete/do/{id}", name="admin_api_teach_chapter_delete")
     */
    public function deleteDoAction($id, ChapterService $chapterService)
    {
        if ($chapterService->hasChild($id))
            return $this->responseError("删除失败，请先删除子章节!");
        $chapterService->del($id);
        $info = $chapterService->getById($id);

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_chapter_index"), [
            'id' => $info['courseId']
        ]);
    }

    /**
     *
     * @Rest\Post("/teach/chapter/updateSort/do/{id}", name="admin_api_teach_chapter_updateSort")
     */
    public function updateSortDoAction($id, Request $request, ChapterService $chapterService)
    {
        $data = $request->request->all();
        $chapterService->updateSort($data);
        return $this->responseMsgRedirect("更新排序成功!", $this->generateUrl("admin_teach_chapter_index", [
            "id" => $id
        ]));
    }

    /**
     * 直播
     * @Rest\Get("/teach/chapter/live/{id}", name="admin_teach_chapter_live")
     */
    public function liveAction($id, ChapterService $chapterService)
    {
        $info = $chapterService->getVideoById($id);
        $data = [];
        $data["id"] = $id;
        $data["pushUrl"] = "";
        $data["playUrl"] = "";
        if($info && $info['liveData']){
            $liveData = $chapterService->parseLiveData($info['liveData'], $info['videoChannel']);
            if($liveData){
                $data["pushUrl"] = $liveData['pushUrl'];
                $data["playUrl"] = json_encode($liveData['playUrl']);
//                if($info['videoChannel'] == 1){
//                    $data["playUrl"] = json_encode($liveData['playUrl']);
//                }else{
//                    $data["playUrl"] = $liveData['playUrl'];
//                }

            }
        }
        $data['info'] = $info;

        return $this->render("@AdminBundle/teach/chapter/live.html.twig", $data);
    }


    /**
     * 直播 生成数据
     * @Rest\Post("/teach/chapter/live/do/{id}", name="admin_api_teach_chapter_live")
     */
    public function liveDoAction($id, ChapterService $chapterService)
    {
        $info = $chapterService->getById($id);
        $chapterService->genLiveData($id);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl("admin_teach_chapter_index", [
            "id" => $info['courseId']
        ]));
    }

    /**
     * 点播
     * @Rest\Get("/teach/chapter/vod/{id}", name="admin_teach_chapter_vod")
     */
    public function vodAction($id, Form $form, ChapterService $chapterService, TengxunyunVodService $tengxunyunVodService, AliyunVodService $aliyunVodService)
    {
        $info = $chapterService->getVideoById($id);

        if(!$info){
            $vodAdapter = $this->getOption("app.vod.adapter");
        }else{
            $vodAdapter = $info['videoChannel'];
        }

        $form->text("视频id")->field("videoId")->placeholder("如果不输入视频id，可以通过下面控件上传视频")->defaultValue(isset($info['videoId']) ? $info['videoId'] : "");
        $form->disableSubmit();

        $formData = $form->create($this->generateUrl("admin_api_teach_chapter_vod", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        $data['vodAdapter'] = $vodAdapter;
        $data['userId'] = $aliyunVodService->getConfigUserId();
        $data['tengxunyunAppId'] = $tengxunyunVodService->getAppId();
        $data['fileName'] = $chapterService->getVideoName($id);
        $data['region'] = $chapterService->getRegion();
        $data['videoInfo'] = $info;

        if($vodAdapter == 2){
            $play = $aliyunVodService->getVodPlayInfo($info["videoId"]);
            $data['palyAuth'] = $play['playAuth'];
        }


        return $this->render("@AdminBundle/teach/chapter/video.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/teach/chapter/video/do/{id}", name="admin_api_teach_chapter_vod")
     */
    public function vodDoAction($id, Request $request, ChapterService $chapterService)
    {
        $type = 2; //点播
        $videoId = $request->get("videoId");

        if (!$videoId) return $this->responseError("视频id不能为空!");

        $videoChannel = $this->getOption("app.vod.adapter");
        if (!$videoChannel)  return $this->responseError("请先设置点播服务商!");

        $chapterService->addVideos($id, $type, $videoChannel, $videoId);

        if ($this->error()->has()) {
            return $this->responseError($this->error()->getLast());
        }

        $chapterInfo = $chapterService->getById($id);
        $courseId = $chapterInfo["courseId"];
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl("admin_teach_chapter_index", [
            "id" => $courseId
        ]));
    }

    /**
     *
     * @Rest\Get("/teach/chapter/materials/{id}", name="admin_teach_chapter_materials")
     */
    public function materialsAction($id, Form $form, ChapterService $chapterService)
    {
        $info = $chapterService->getMaterialsById($id);
        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "course_materials"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 100;
        $options["data-max-file-size"] = 1024 * 50; //50m
        $options["data-required"] = 1;
        if ($info) $options['data-initial-preview'] = $info['path'];
        if ($info) $options['data-initial-preview-config'] = $chapterService->getInitialPreviewConfig($info['path']);

        $form->file("附件")->field("path")->attr($options)->defaultValue(isset($info['path']) ? $info['path'] : "");

        $formData = $form->create($this->generateUrl("admin_api_teach_chapter_materials", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/chapter/materials.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/teach/chapter/materials/do/{id}", name="admin_api_teach_chapter_materials")
     */
    public function materialsDoAction($id, Request $request, ChapterService $chapterService)
    {
        $path = $request->get("path");
        if (!$path) return $this->responseError("附件不能为空!");

        $chapterService->addMaterials($id, $path);

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl("admin_teach_chapter_index", [
            "id" => $id
        ]));
    }
}
