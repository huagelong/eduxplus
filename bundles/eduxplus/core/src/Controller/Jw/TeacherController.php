<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 21:51
 */

namespace Eduxplus\CoreBundle\Controller\Jw;


use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\CoreBundle\Service\Jw\SchoolService;
use Eduxplus\CoreBundle\Service\Jw\TeacherService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\CoreBundle\Entity\JwTeacher;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class TeacherController extends BaseAdminController
{
    /**
     * @Route("/jw/teacher/index", name="admin_jw_teacher_index")
     */
    public function indexAction(Request $request, Grid $grid, TeacherService $teacherService)
    {
        $pageSize = 40;
        $grid->setListService($teacherService, "getList");
        $grid->text("#")->field("id")->sort("a.id");
        $grid->text("名称")->field("name");
        $grid->image("头像")->field("gravatar");
        $grid->text("类型")->field("type")->options([-1 => "全部", 1 => "网课老师", 2 => "分校老师"]);
        $grid->text("分校")->field("school");
        $grid->boole2("是否锁定？")->field("status")->actionCall("admin_api_jw_teacher_switchStatus", function ($obj) {
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_jw_teacher_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"确认更改状态吗?\" {$checkStr} >";
            return $str;
        });
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");
        $grid->gbButton("添加")->route("admin_jw_teacher_add")->url($this->generateUrl("admin_jw_teacher_add"))->iconClass("fas fa-plus")->styleClass("btn-success");

        $grid->stext("名称")->field("a.name");
        $grid->sdaterange("创建时间")->field("a.createdAt");

        //编辑等
        $grid->setTableAction('admin_jw_teacher_view', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('admin_jw_teacher_view', ['id' => $id]);
            $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="fas fa-eye"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_jw_teacher_edit', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('admin_jw_teacher_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="800px" data-height="600px" title="编辑" data-title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_jw_teacher_delete', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('admin_api_jw_teacher_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?"  title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        //批量删除
        $bathDelUrl = $this->genUrl("admin_api_jw_teacher_bathdelete");
        $grid->setBathDelete("admin_api_jw_teacher_bathdelete", $bathDelUrl);

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@CoreBundle/jw/teacher/index.html.twig", $data);
    }

    /**
     * @Route("/jw/teacher/add", name="admin_jw_teacher_add")
     */
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
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@CoreBundle/jw/teacher/add.html.twig", $data);
    }

    /**
     * @Route("/jw/teacher/add/do", name="admin_api_jw_teacher_add")
     */
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

    /**
     * @Route("/jw/teacher/view/{id}", name="admin_jw_teacher_view")
     */
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
        $data = [];
        $data["formData"] = $formData;
        $data['info'] = $info;
        return $this->render("@CoreBundle/jw/teacher/view.html.twig", $data);
    }

    /**
     * @Route("/jw/teacher/edit/{id}", name="admin_jw_teacher_edit")
     */
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
        $data = [];
        $data["formData"] = $formData;
        $data['info'] = $info;
        return $this->render("@CoreBundle/jw/teacher/edit.html.twig", $data);
    }

    /**
     * @Route("/jw/teacher/edit/do/{id}", name="admin_api_jw_teacher_edit")
     */
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

    /**
     * @Route("/jw/teacher/delete/do/{id}", name="admin_api_jw_teacher_delete")
     */
    public function deleteAction($id, TeacherService $teacherService)
    {
        $teacherService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_jw_teacher_index"));
    }

    /**
     * @Route("/jw/teacher/bathdelete/do", name="admin_api_jw_teacher_bathdelete")
     */
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

    /**
     * @Route("/jw/teacher/switchStatus/do/{id}", name="admin_api_jw_teacher_switchStatus")
     */
    public function switchStatusAction($id, Request $request, TeacherService $teacherService)
    {
        $state = (int) $request->get("state");
        $teacherService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }
}
