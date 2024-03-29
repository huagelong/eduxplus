<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 21:51
 */

namespace Eduxplus\EduxBundle\Controller\Admin\Jw;


use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\EduxBundle\Service\Jw\SchoolService;
use Eduxplus\EduxBundle\Service\Jw\TeacherService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\EduxBundle\Entity\JwTeacher;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class TeacherController extends BaseAdminController
{
    
    public function indexAction(Request $request, Grid $grid, TeacherService $teacherService)
    {
        $pageSize = 40;
        $grid->setListService($teacherService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("名称")->field("name");
        $grid->image("头像")->field("gravatar");
        $grid->text("类型")->field("type")->options([-1 => "全部", 1 => "网课老师", 2 => "分校老师"]);
        $grid->text("分校")->field("school");
        $grid->boole2("是否锁定？")->field("status")->actionCall("admin_api_jw_teacher_switchStatus", function ($obj)  use ($teacherService) {
            $id = $teacherService->getPro($obj, "id");
            $defaultValue = $teacherService->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_jw_teacher_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"确认更改状态吗?\" {$checkStr} >";
            return $str;
        });
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");
        $grid->stext("名称")->field("a.name");
        $grid->sdaterange("创建时间")->field("a.createdAt");

        $grid->gbAddButton("admin_jw_teacher_add");

        $grid->viewAction("admin_jw_teacher_view")
            ->editAction("admin_jw_teacher_edit")
            ->deleteAction("admin_api_jw_teacher_delete");
        //批量删除
        $grid->setBathDelete("admin_api_jw_teacher_bathdelete");

        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    
    public function addAction(Form $form, TeacherService $teacherService)
    {
        $form->text("名称")->field("name")->isRequire(1);
        $form->select("类型")->field("type")->isRequire(1)->options(["网课老师" => 1, "分校老师" => 2]);

        $attr = [];
        $attr["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "teacher_gravatar"]);
        $attr["data-min-file-count"] = 1;
        $attr['data-max-total-file-count'] = 100;
        $attr["data-max-file-size"] = 1024 * 5; //5m
        $attr["data-required"] = 1;

        $form->file("头像")->field("gravatar")->attr($attr);

        $form->select("分校")->field("schoolId")->isRequire(1)->options(function () use ($teacherService) {
            return $teacherService->schoolSelect();
        });

        $form->boole("锁定？")->field("status")->isRequire(1);

        $form->richEditor("描述")->field("descr")->attr(['data-width' => 800, 'data-height' => 200]);
        $formData = $form->create($this->generateUrl("admin_api_jw_teacher_add"));
        return $this->content()->title("添加老师")
                ->breadcrumb("老师管理","admin_jw_teacher_index")
                ->renderAdd($formData);
    }

    
    public function addDoAction(Request $request, TeacherService $teacherService)
    {
        $name = $request->get("name");
        $descr = $request->get("descr");
        $type = (int) $request->get("type");
        $schoolId = $request->get("schoolId");
        $status = $request->get("status");
        $status = $status == "on" ? 1 : 0;
        $gravatar = $request->get("gravatar");

        $realGravatar = $gravatar ? json_decode($gravatar, true) : "";
        if (!$name) return $this->responseError("名称不能为空!");
        if (!$realGravatar) return $this->responseError("头像不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("名称不能大于30字!");
        if ($teacherService->getByName($name)) return $this->responseError("名称已存在!");


        $teacherService->add($name, $descr, $type, $schoolId, $status, $gravatar);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_jw_teacher_index"));
    }

    
    public function viewAction($id, View $view, TeacherService $teacherService)
    {
        $info = $teacherService->getById($id);
        $view->text("名称")->field("name")->defaultValue($info['name']);
        $attr = [];
        $attr["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "teacher_gravatar"]);
        $attr["data-min-file-count"] = 1;
        $attr['data-max-total-file-count'] = 100;
        $attr["data-max-file-size"] = 1024 * 5; //5m
        $attr["data-required"] = 1;
        if ($info) $attr['data-initial-preview'] = $info['gravatar'];
        if ($info) $attr['data-initial-preview-config'] = $teacherService->getInitialPreviewConfig($info['gravatar']);

        $view->file("头像")->field("gravatar")->defaultValue($info["gravatar"])->attr($attr);

        $view->select("类型")->field("type")->defaultValue($info['type'])->options(["网课老师" => 1, "分校老师" => 2]);
        $view->select("分校")->field("schoolId")->defaultValue($info['schoolId'])->options(function () use ($teacherService) {
            return $teacherService->schoolSelect();
        });

        $view->boole("锁定？")->field("status")->defaultValue($info['status']);
        $view->richEditor("描述")->field("descr")->defaultValue($info['descr'])->attr(['data-width' => 800, 'data-height' => 200]);

        $formData = $view->create();
        return $this->content()->renderView($formData);
    }

    
    public function editAction($id, Form $form, TeacherService $teacherService)
    {
        $info = $teacherService->getById($id);
        $form->text("名称")->field("name")->isRequire(1)->defaultValue($info['name']);
        $attr = [];
        $attr["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "teacher_gravatar"]);
        $attr["data-min-file-count"] = 1;
        $attr['data-max-total-file-count'] = 100;
        $attr["data-max-file-size"] = 1024 * 5; //5m
        $attr["data-required"] = 1;
        if ($info) $attr['data-initial-preview'] = $info['gravatar'];
        if ($info) $attr['data-initial-preview-config'] = $teacherService->getInitialPreviewConfig($info['gravatar']);

        $form->file("头像")->field("gravatar")->defaultValue($info["gravatar"])->attr($attr);

        $form->select("类型")->field("type")->isRequire(1)->defaultValue($info['type'])->options(["网课老师" => 1, "分校老师" => 2]);
        $form->select("分校")->field("schoolId")->isRequire(1)->defaultValue($info['schoolId'])->options(function () use ($teacherService) {
            return $teacherService->schoolSelect();
        });

        $form->boole("锁定？")->field("status")->isRequire(1)->defaultValue($info['status']);
        $form->richEditor("描述")->field("descr")->defaultValue($info['descr'])->attr(['data-width' => 800, 'data-height' => 200]);

        $formData = $form->create($this->generateUrl("admin_api_jw_teacher_edit", ["id" => $id]));
        return $this->content()->renderEdit($formData);
    }

    
    public function editDoAction($id, Request $request, TeacherService $teacherService)
    {
        $name = $request->get("name");
        $descr = $request->get("descr");
        $type = (int) $request->get("type");
        $schoolId = $request->get("schoolId");
        $status = $request->get("status");
        $status = $status == "on" ? 1 : 0;
        $gravatar = $request->get("gravatar");

        if (!$name) return $this->responseError("名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("名称不能大于30字!");
        if ($teacherService->getByName($name, $id)) return $this->responseError("名称已存在!");

        $teacherService->edit($id, $name, $descr, $type, $schoolId, $status,  $gravatar);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_jw_teacher_index"));
    }

    
    public function deleteAction($id, TeacherService $teacherService)
    {
        $teacherService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_jw_teacher_index"));
    }

    
    public function bathdeleteAction(Request $request, TeacherService $teacherService)
    {

        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $teacherService->del($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_jw_teacher_index"));
    }

    
    public function switchStatusAction($id, Request $request, TeacherService $teacherService)
    {
        $state = (int) $request->get("state");
        $teacherService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }
}
