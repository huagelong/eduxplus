<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 19:53
 */
namespace App\Bundle\AdminBundle\Controller\Teach;

use App\Bundle\AdminBundle\Service\Teach\StudyPlanService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class StudyPlanController extends BaseAdminController
{

    /**
     *
     * @Rest\Get("/teach/studyplan/index/{id}", name="admin_teach_studyplan_index")
     */
    public function indexAction($id, Request $request,Grid $grid, StudyPlanService $studyPlanService)
    {
        $pageSize = 30;
        $page = $request->get("page");
        $page = $page?$page:1;

        $data = [];
        list($pagination, $all) = $studyPlanService->getList($id, $page, $pageSize);
        $data['planList'] = $all;
        $data['id'] = $id;
        $data['pagination'] = $pagination;

        return $this->render("@AdminBundle/teach/studyplan/index.html.twig", $data);
    }

    /**
     *
     * @Rest\Get("/teach/studyplan/add/{id}", name="admin_teach_studyplan_add")
     */
    public function addAction($id, Form $form, Request $request, StudyPlanService $studyPlanService)
    {
        $form->setFormField("名称", 'text', 'name', 1);
        $form->setFormField("是否当前默认计划", 'boole', 'isDefault', 1);
        $form->setFormField("是否有挡板", 'boole', 'isBlock', 1);
        $form->setFormField("预计报名时间", 'datetime', 'applyedAt');
        $form->setFormField("课程", 'search_select', 'courseId[]', 1, "", function (){
            return $this->generateUrl("admin_api_teach_studyplan_searchCourseDo");
        });

        $form->setFormField("描述", 'textarea', 'descr');

        $formData = $form->create($this->generateUrl("admin_api_teach_studyplan_add", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/studyplan/add.html.twig", $data);
    }

    /**
     * @Rest\Get("/api/teach/studyplan/searchCourseDo", name="admin_api_teach_studyplan_searchCourseDo")
     */
    public function searchCourseDoAction(Request $request, StudyPlanService $studyPlanService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $studyPlanService->searchCourseName($kw);
        return $data;
    }

    /**
     *
     * @Rest\Post("/api/teach/studyplan/addDo/{id}", name="admin_api_teach_studyplan_add")
     */
    public function addDoAction($id, Request $request, StudyPlanService $studyPlanService)
    {
        $name = $request->get("name");
        $isDefault = $request->get("isDefault");
        $isBlock = $request->get("isBlock");
        $applyedAt = $request->get("applyedAt");
        $courseIds = $request->get("courseId");
        $descr = $request->get("descr");

        $isDefault = $isDefault == "on" ? 1 : 0;
        $isBlock = $isBlock == "on" ? 1 : 0;

        if (! $name) return $this->responseError("课程计划名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("课程计划名称不能大于50字!");

        if(!$courseIds) return $this->responseError("课程必须选择!");

        $uid = $this->getUid();
        $studyPlanService->add($id, $name, $isDefault, $isBlock, $applyedAt, $courseIds, $uid, $descr);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_teach_studyplan_index', [
            'id' => $id
        ]));
    }

    /**
     *
     * @Rest\Get("/teach/studyplan/edit/{id}", name="admin_teach_studyplan_edit")
     */
    public function editAction($id, Form $form, StudyPlanService $studyPlanService)
    {
        $info = $studyPlanService->getById($id);

        $select = $studyPlanService->chapterSelect();

        $form->setFormField("名称", 'text', 'name', 1, $info['name']);
        $form->setFormField("父章节", 'select', 'parentId', 1, $info['parentId'], function () use ($select) {
            return $select;
        });
        $form->setFormField("上课时间", 'datetime', 'openTime', 0, date('Y-m-d H:i', $info['openTime']));

        $teacherIds = $studyPlanService->getTeacherIds($id);
        $form->setFormField("上课老师", 'multiSelect', 'teachers[]', 0, $teacherIds, function () use ($studyPlanService) {
            return $studyPlanService->getTeachers();
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

        $formData = $form->create($this->generateUrl("admin_api_teach_studyplan_edit", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        $data['id'] = $id;
        return $this->render("@AdminBundle/teach/studyplan/edit.html.twig", $data);
    }

    /**
     *
     * @Rest\Post("/api/teach/studyplan/editDo/{id}", name="admin_api_teach_studyplan_edit")
     */
    public function editDoAction($id, Request $request, StudyPlanService $studyPlanService)
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

        $studyPlanService->edit($id, $name, $teachers, $parentId, $openTime, $studyWay, $isFree, $sort);

        $info = $studyPlanService->getById($id);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_teach_studyplan_index', [
            'id' => $info['courseId']
        ]));
    }

    /**
     *
     * @Rest\Post("/api/teach/studyplan/deleteDo/{id}", name="admin_api_teach_studyplan_delete")
     */
    public function deleteDoAction($id, StudyPlanService $studyPlanService)
    {
        if ($studyPlanService->hasSub($id))
            return $this->responseError("删除失败，请先删除课程数据!");
        $studyPlanService->del($id);
        $info = $studyPlanService->getById($id);

        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_teach_studyplan_index"), [
            'id' => $info['productId']
        ]);
    }

    /**
     *
     * @Rest\Post("/api/teach/studyplan/updateSort/{id}", name="admin_api_teach_studyplan_updateSort")
     */
    public function updateSortDoAction($id, Request $request, StudyPlanService $studyPlanService)
    {
        $data = $request->request->all();
        $studyPlanService->updateSort($id, $data);
        $info = $studyPlanService->getById($id);
        return $this->responseSuccess("更新排序成功!", $this->generateUrl("admin_teach_studyplan_index", [
            "id" => $info['productId']
        ]));
    }

}
