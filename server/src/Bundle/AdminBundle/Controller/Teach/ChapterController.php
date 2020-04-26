<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 19:53
 */
namespace App\Bundle\AdminBundle\Controller\Teach;

use App\Bundle\AdminBundle\Service\Teach\ChapterService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
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
        $data['all'] = $chapterService->getChapterTree(0);
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/chapter/index.html.twig", $data);
    }

    /**
     *
     * @Rest\Get("/teach/chapter/add/{id}", name="admin_teach_chapter_add")
     */
    public function addAction($id, Form $form, Request $request, ChapterService $chapterService)
    {
        $select = $chapterService->chapterSelect();
        $parentId = $request->get("pid");

        $form->setFormField("名称", 'text', 'name', 1);
        $form->setFormField("父章节", 'select', 'parentId', 1, $parentId, function () use ($select) {
            return $select;
        });
        $form->setFormField("上课时间", 'datetime', 'openTime');

        $form->setFormField("上课老师", 'multiSelect', 'teachers[]', 0, "", function () use ($chapterService) {
            return $chapterService->getTeachers();
        });

        $form->setFormField("学习方式", "select", "studyWay", 1, "", function () {
            return [
                "线上" => 1,
                "线下" => 2,
                "混合" => 3
            ];
        });
        $form->setFormField("是否免费", 'boole', 'isFree', 1);

        $form->setFormField("排序", 'text', 'sort', 1, 0);

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
     * @Rest\Post("/api/teach/chapter/addDo/{id}", name="admin_api_teach_chapter_add")
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

        if (! $name)
            return $this->responseError("章节名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50)
            return $this->responseError("章节名称不能大于50字!");

        $openTime = $openTime ? strtotime($openTime) : "";

        $chapterService->add($name, $teachers, $parentId, $openTime, $studyWay, $isFree, $sort, $id);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_teach_chapter_index', [
            'id' => $id
        ]));
    }

    /**
     *
     * @Rest\Get("/teach/chapter/edit/{id}", name="admin_teach_chapter_edit")
     */
    public function editAction($id, Form $form, ChapterService $chapterService)
    {
        $info = $chapterService->getById($id);

        $select = $chapterService->chapterSelect();

        $form->setFormField("名称", 'text', 'name', 1, $info['name']);
        $form->setFormField("父章节", 'select', 'parentId', 1, $info['parentId'], function () use ($select) {
            return $select;
        });
        $form->setFormField("上课时间", 'datetime', 'openTime', 0, date('Y-m-d H:i', $info['openTime']));

        $teacherIds = $chapterService->getTeacherIds($id);
        $form->setFormField("上课老师", 'multiSelect', 'teachers[]', 0, $teacherIds, function () use ($chapterService) {
            return $chapterService->getTeachers();
        });

        $form->setFormField("学习方式", "select", "studyWay", 1, $info['studyWay'], function () {
            return [
                "线上" => 1,
                "线下" => 2,
                "混合" => 3
            ];
        });
        $form->setFormField("是否免费", 'boole', 'isFree', 1, $info['isFree']);

        $form->setFormField("排序", 'text', 'sort', 1, $info['sort']);

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
     * @Rest\Post("/api/teach/chapter/editDo/{id}", name="admin_api_teach_chapter_edit")
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

        if (! $name)
            return $this->responseError("章节名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50)
            return $this->responseError("章节名称不能大于50字!");

        $openTime = $openTime ? strtotime($openTime) : "";

        $chapterService->edit($id, $name, $teachers, $parentId, $openTime, $studyWay, $isFree, $sort);

        $info = $chapterService->getById($id);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_teach_chapter_index', [
            'id' => $info['courseId']
        ]));
    }

    /**
     *
     * @Rest\Post("/api/teach/chapter/deleteDo/{id}", name="admin_api_teach_chapter_delete")
     */
    public function deleteDoAction($id, ChapterService $chapterService)
    {
        if ($chapterService->hasChild($id))
            return $this->responseError("删除失败，请先删除子章节!");
        $chapterService->del($id);
        $info = $chapterService->getById($id);

        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_teach_chapter_index"), [
            'id' => $info['courseId']
        ]);
    }

    /**
     *
     * @Rest\Post("/api/teach/chapter/updateSort/{id}", name="admin_api_teach_chapter_updateSort")
     */
    public function updateSortDoAction($id, Request $request, ChapterService $chapterService)
    {
        $data = $request->request->all();
        $chapterService->updateSort($data);
        return $this->responseSuccess("更新排序成功!", $this->generateUrl("admin_teach_chapter_index", [
            "id" => $id
        ]));
    }

    /**
     *
     * @Rest\Get("/teach/chapter/video/{id}", name="admin_teach_chapter_video")
     */
    public function videoAction($id, Form $form, ChapterService $chapterService)
    {
        $info = $chapterService->getVideoById($id);
        $form->setFormField("视频类型", 'select', 'type', 1, $info['type'], function () {
            return [
                "直播" => 1,
                "录播" => 2
            ];
        });

        $form->setFormField("渠道", 'select', 'videoChannel', 1, $info['videoChannel'], function () {
            return [
                "cc视频" => 1
            ];
        });

        $form->setFormField("渠道数据", 'textarea', 'channelData', 1, $info['channelData']);

        $form->disableSubmit();

        $formData = $form->create($this->generateUrl("admin_api_teach_chapter_video", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/chapter/video.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/api/teach/chapter/videoDo/{id}", name="admin_api_teach_chapter_video")
     */
    public function videoDoAction($id, Request $request, ChapterService $chapterService)
    {
        $type = (int) $request->get("type");
        $videoChannel = $request->get("videoChannel");
        $channelData = $request->get("channelData");

        if(!$channelData) return $this->responseError("播放渠道数据不能为空!");

        $chapterService->addVideos($id, $type, $videoChannel, $channelData);

        return $this->responseSuccess("操作成功!", $this->generateUrl("admin_teach_chapter_index", [
            "id" => $id
        ]));
    }

    /**
     *
     * @Rest\Post("/teach/chapter/materials/{id}", name="admin_teach_chapter_materials")
     */
    public function materialsAction($id, Form $form, ChapterService $chapterService){
        $info = $chapterService->getVideoById($id);
        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"course_materials"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 100;
        $options["data-max-file-size"] = 1024*50;//50m
        $options["data-required"] = 1;

        $form->setFormAdvanceField("附件", "file", 'path' , $options, $info['path']);

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
     * @Rest\Post("/api/teach/chapter/materialsDo/{id}", name="admin_api_teach_chapter_materials")
     */
    public function materialsDoAction($id, Request $request, ChapterService $chapterService)
    {
        $path = $request->get("path");
        if(!$path) return $this->responseError("附件不能为空!");

        $chapterService->addMaterials($id, $path);

        return $this->responseSuccess("操作成功!", $this->generateUrl("admin_teach_chapter_index", [
            "id" => $id
        ]));
    }

}
