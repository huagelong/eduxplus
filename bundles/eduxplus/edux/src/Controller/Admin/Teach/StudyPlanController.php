<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/19 19:53
 */

namespace Eduxplus\EduxBundle\Controller\Admin\Teach;

use Eduxplus\EduxBundle\Service\Teach\CourseService;
use Eduxplus\EduxBundle\Service\Teach\StudyPlanService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class StudyPlanController extends BaseAdminController
{

    
    public function indexAction($id, Request $request, Grid $grid, StudyPlanService $studyPlanService)
    {
        $pageSize = 30;
        $page = $request->get("page");
        $page = $page ? $page : 1;

        $data = [];
        list($pagination, $all) = $studyPlanService->getList($id, $page, $pageSize);
        $data['planList'] = $all;
        $data['id'] = $id;
        $data['pagination'] = $pagination;
        return $this->render("@EduxBundle/teach/studyplan/index.html.twig", $data);
    }

    
    public function addAction($id, Form $form, Request $request, StudyPlanService $studyPlanService)
    {
        $form->text("名称")->field("name")->isRequire(1)->placeholder("第x次开课")->defaultValue("第x次开课");
        $form->boole("当前默认计划？")->field("isDefault")->isRequire(1);
        $form->boole("有挡板？")->field("isBlock")->isRequire(1);
        $form->boole("是否开启?")->field("status")->isRequire(1)->defaultValue(1);
        $form->searchMultipleSelect("课程")->field("courseId[]")->isRequire(1)->options([$this->generateUrl("admin_api_glob_searchCourseDo"), []]);
        $form->textarea("描述")->field("descr");

        $formData = $form->create($this->generateUrl("admin_api_teach_studyplan_add", [
            'id' => $id
        ]));
        return $this->content()->title("添加章节")
               ->renderAdd($formData);
    }

    
    public function addDoAction($id, Request $request, StudyPlanService $studyPlanService)
    {
        $name = $request->get("name");
        $isDefault = $request->get("isDefault");
        $isBlock = $request->get("isBlock");
        $applyedAt = $request->get("applyedAt");
        $courseIds = $request->get("courseId");
        $descr = $request->get("descr");
        $status = $request->get("status");

        $isDefault = $isDefault == "on" ? 1 : 0;
        $isBlock = $isBlock == "on" ? 1 : 0;
        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("课程计划名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("课程计划名称不能大于50字!");

        if (!$courseIds) return $this->responseError("课程必须选择!");

        $uid = $this->getUid();
        $studyPlanService->add($id, $name, $isDefault, $isBlock, $applyedAt, $courseIds, $uid, $descr, $status);

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_teach_studyplan_index', [
            'id' => $id
        ]));
    }

    
    public function editAction($id, Form $form, StudyPlanService $studyPlanService, CourseService $courseService)
    {
        $info = $studyPlanService->getById($id);

        $form->text("名称")->field("name")->isRequire(1)->placeholder("第x次开课")->defaultValue("第x次开课")->defaultValue($info['name']);
        $form->boole("当前默认计划？")->field("isDefault")->isRequire(1)->defaultValue($info['isDefault']);
        $form->boole("有挡板？")->field("isBlock")->isRequire(1)->defaultValue($info['isBlock']);
        $form->boole("是否开启?")->field("status")->isRequire(1)->defaultValue($info['status']);
        $form->datetime("预计报名时间")->field("applyedAt")->defaultValue($info['applyedAt'] ? date('Y-m-d H:i:s', $info['applyedAt']) : "");
        $form->searchMultipleSelect("课程")->field("courseId[]")->isRequire(1)
            ->options(function ()use($info,$courseService){
                $courses = !$info['sub']?[]:$courseService->getSelectByIds($info['sub']);
                return [$this->generateUrl("admin_api_glob_searchCourseDo"),$courses];
            })
            ->defaultValue($info['sub']);
        $form->textarea("描述")->field("descr")->defaultValue($info['descr']);

        $formData = $form->create($this->generateUrl("admin_api_teach_studyplan_edit", [
            'id' => $id
        ]));
        return $this->content()->renderEdit($formData);
    }

    
    public function editDoAction($id, Request $request, StudyPlanService $studyPlanService)
    {
        $name = $request->get("name");
        $isDefault = $request->get("isDefault");
        $isBlock = $request->get("isBlock");
        $applyedAt = $request->get("applyedAt");
        $courseIds = $request->get("courseId");
        $descr = $request->get("descr");
        $status = $request->get("status");

        $isDefault = $isDefault == "on" ? 1 : 0;
        $isBlock = $isBlock == "on" ? 1 : 0;
        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("课程计划名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("课程计划名称不能大于50字!");

        if (!$courseIds) return $this->responseError("课程必须选择!");

        $studyPlanService->edit($id, $name, $isDefault, $isBlock, $applyedAt, $courseIds, $descr, $status);

        $info = $studyPlanService->getById($id);

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_teach_studyplan_index', [
            'id' => $info['productId']
        ]));
    }

    
    public function deleteDoAction($id, StudyPlanService $studyPlanService)
    {
        if ($studyPlanService->hasSub($id))
            return $this->responseError("删除失败，请先删除课程数据!");
        $studyPlanService->del($id);
        $info = $studyPlanService->getById($id);

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_studyplan_index", ['id' => $info['productId']]));
    }

    
    public function deleteSubDoAction($id, StudyPlanService $studyPlanService)
    {
        $info = $studyPlanService->getSubById($id);
        $studyPlanService->delsub($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_studyplan_index", [
            'id' => $info['study_plan']['productId']
        ]));
    }

    
    public function updateSortDoAction($id, Request $request, StudyPlanService $studyPlanService)
    {
        $data = $request->request->all();
        $studyPlanService->updateSort($id, $data);
        $info = $studyPlanService->getById($id);
        return $this->responseMsgRedirect("更新排序成功!", $this->generateUrl("admin_teach_studyplan_index", [
            "id" => $info['productId']
        ]));
    }

    
    public function switchStatusAction($id, StudyPlanService $studyPlanService, Request $request)
    {
        $info = $studyPlanService->getSimpleById($id);
        $state = $info['status'] ? 0 : 1;
        $studyPlanService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_teach_studyplan_index', [
            'id' => $info['productId']
        ]));
    }
}
