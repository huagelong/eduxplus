<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 21:51
 */

namespace App\Bundle\AdminBundle\Controller\Jw;


use App\Bundle\AdminBundle\Service\Jw\SchoolService;
use App\Bundle\AdminBundle\Service\Jw\TeacherService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use App\Entity\JwTeacher;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class TeacherController extends BaseAdminController
{
    /**
     * @Rest\Get("/jw/teacher/index", name="admin_jw_teacher_index")
     */
    public function indexAction(Request $request, Grid $grid, TeacherService $teacherService){
        $pageSize = 20;
        $grid->setListService($teacherService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("名称", "text", "name");
        $grid->setTableColumn("类型", "text", "type", "", [-1=>"全部",1=>"网课老师", 2=>"分校老师"]);
        $grid->setTableColumn("分校", "text", "school");

        $grid->setTableActionColumn("admin_api_jw_teacher_switchStatus", "锁定？", "boole2", "status", null,null,function($obj){
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_jw_teacher_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue?"checked":"";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"确认更改状态吗?\" {$checkStr} >";
            return $str;
        });

        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        $grid->setGridBar("admin_jw_teacher_add","添加", $this->generateUrl("admin_jw_teacher_add"), "fas fa-plus", "btn-success");

        $grid->setSearchField("名称", "text", "a.name");
        $grid->setSearchField("创建时间", "daterange", "a.createdAt");

        //编辑等
        $grid->setTableAction('admin_jw_teacher_edit', function($obj){
            $id = $obj["id"];
            $url = $this->generateUrl('admin_jw_teacher_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-width="800px" data-height="600px" title="编辑" data-title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_jw_teacher_delete', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('admin_api_jw_teacher_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?"  title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/jw/teacher/index.html.twig", $data);

    }

    /**
     * @Rest\Get("/jw/teacher/add", name="admin_jw_teacher_add")
     */
    public function addAction(Form $form, TeacherService $teacherService){
        $form->setFormField("名称", 'text', 'name' ,1);
        $form->setFormField("类型", "select", "type", 1, "",function(){
            return ["网课老师"=>1, "分校老师"=>2];
        });
        $form->setFormField("分校", "select", "schoolId", 1, "", function()use($teacherService){
            return $teacherService->schoolSelect();
        });
        $form->setFormField("锁定？", 'boole', 'status', 1);
        $form->setFormField("描述", 'rich_editor', 'descr' ,0,'','','',['data-width'=>800, 'data-height'=>200]);
        $formData = $form->create($this->generateUrl("admin_api_jw_teacher_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/jw/teacher/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/jw/teacher/addDo", name="admin_api_jw_teacher_add")
     */
    public function addDoAction(Request $request, TeacherService $teacherService){
        $name = $request->get("name");
        $descr = $request->get("descr");
        $type = (int) $request->get("type");
        $schoolId = $request->get("schoolId");
        $status = $request->get("status");
        $status = $status=="on"?1:0;

        if(!$name) return $this->responseError("名称不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("名称不能大于30字!");
        if($teacherService->getByName($name)) return $this->responseError("名称已存在!");


        $teacherService->add($name, $descr, $type, $schoolId, $status);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_jw_teacher_index"));
    }

    /**
     * @Rest\Get("/jw/teacher/edit/{id}", name="admin_jw_teacher_edit")
     */
    public function editAction($id, Form $form, TeacherService $teacherService){
        $info = $teacherService->getById($id);
        $form->setFormField("名称", 'text', 'name' ,1, $info['name']);
        $form->setFormField("类型", "select", "type", 1, $info['type'],function(){
            return ["网课老师"=>1, "分校老师"=>2];
        });
        $form->setFormField("分校", "select", "schoolId", 1, $info['schoolId'], function()use($teacherService){
            return $teacherService->schoolSelect();
        });
        $form->setFormField("锁定？", 'boole', 'status', 1, $info['status']);
        $form->setFormField("描述", 'rich_editor', 'descr' ,0,$info['descr'],'','',['data-width'=>800, 'data-height'=>200]);
        $formData = $form->create($this->generateUrl("admin_api_jw_teacher_edit", ["id"=>$id]));
        $data = [];
        $data["formData"] = $formData;
        $data['info'] = $info;
        return $this->render("@AdminBundle/jw/teacher/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/jw/teacher/editDo/{id}", name="admin_api_jw_teacher_edit")
     */
    public function editDoAction($id, Request $request, TeacherService $teacherService){
        $name = $request->get("name");
        $descr = $request->get("descr");
        $type = (int) $request->get("type");
        $schoolId = $request->get("schoolId");
        $status = $request->get("status");
        $status = $status=="on"?1:0;

        if(!$name) return $this->responseError("名称不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("名称不能大于30字!");
        if($teacherService->getByName($name, $id)) return $this->responseError("名称已存在!");

        $teacherService->edit($id, $name, $descr, $type, $schoolId, $status);

        return $this->responseSuccess("编辑成功!", $this->generateUrl("admin_jw_teacher_index"));
    }

    /**
     * @Rest\Post("/api/jw/teacher/deleteDO/{id}", name="admin_api_jw_teacher_delete")
     */
    public function deleteAction($id, TeacherService $teacherService){
        $teacherService->del($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_jw_teacher_index"));
    }

    /**
     * @Rest\Post("/api/jw/teacher/switchStatusDo/{id}", name="admin_api_jw_teacher_switchStatus")
     */
    public function switchStatusAction($id, Request $request, TeacherService $teacherService){
        $state = (int) $request->get("state");
        $teacherService->switchStatus($id, $state);
        return $this->responseSuccess("操作成功!");
    }

}
