<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 09:57
 */

namespace App\Bundle\AdminBundle\Controller\Teach;

use App\Bundle\AdminBundle\Service\Teach\CategoryService;
use App\Bundle\AdminBundle\Service\Teach\CourseService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class CourseController extends BaseAdminController
{

    /**
     * @Rest\Get("/teach/course/index", name="admin_teach_course_index")
     */
    public function indexAction(Request $request, Grid $grid,CourseService $courseService){

        $pageSize = 20;
        $grid->setListService($courseService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("课程名称", "text", "name","a.name");
        $grid->setTableColumn("类型", "text", "type", [1=>"线上", 2=>"线下", 3=>"混合"]);
        $grid->setTableColumn("品类", "text", "brand");
        $grid->setTableColumn("类目", "text", "category");
        $grid->setTableColumn("封面图", "image", "bigImg");
        $grid->setTableColumn("创建人", "text", "creater");
        $grid->setTableColumn("是否上架", "boole2", "status", [0=>"下架",1=>"上架"]);
        $grid->setTableActionColumn("admin_api_teach_course_switchStatus", "是否上架", "boole2", "status", null,null,function($obj){
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_teach_course_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue?"checked":"";
            $confirmStr = $defaultValue? "确认要下架吗？":"确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });

        $grid->setTableColumn("课时", "text", "courseHourView");
        $grid->setTableColumn("上课校区", "text", "school");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        //编辑等
        $grid->setTableAction('admin_teach_course_edit', function($obj){
            $id = $obj['id'];
            $url = $this->generateUrl('admin_teach_course_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_teach_course_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_api_teach_course_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?"  class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $grid->setGridBar("admin_teach_course_add","添加", $this->generateUrl("admin_teach_course_add"), "fas fa-plus", "btn-success");

        //搜索
        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("课程名称", "text", "a.name");
        $grid->setSearchField("类型", "select", "a.type", function(){
            return ["全部"=>-1,"线上"=>1, "线下"=>2, "混合"=>3];
        });
        $grid->setSearchField("是否上架", "select", "a.status", function(){
            return ["全部"=>-1,"下架"=>0, "上架"=>1];
        });
        $grid->setSearchField("校区", "select", "a.schoolId", function()use($courseService){
            return $courseService->getSchools();
        });

        $grid->setSearchField("创建人", "search_select", "a.createUid",function(){
            return $this->generateUrl("admin_api_teach_course_searchUserDo");
        });
        $grid->setSearchField("创建时间", "daterange", "a.createdAt");

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/teach/course/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/teach/course/add", name="admin_teach_course_add")
     */
    public function addAction(Form $form, CourseService $courseService, CategoryService $categoryService){

        $form->setFormField("课程名称", 'text', 'name' ,1);
        $form->setFormField("类型", 'select', 'type' ,1, "", function(){
            return ["线上"=>1, "线下"=>2, "混合"=>3];
        });

        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_course"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024*2;//2m
        $options["data-required"] = 0;

        $form->setFormAdvanceField("封面图", 'file', 'bigImg' ,$options);
        $form->setFormField("类目", 'select', 'categoryId', 1, "", function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });
        $form->setFormField("课时", 'text', 'courseHour' ,1, "","", "课时为整数");
        $form->setFormField("上课校区", 'select', 'schoolId', 0, "", function() use($courseService){
            return $courseService->getSchools();
        });
        $form->setFormField("简介", 'textarea', 'descr');

        $formData = $form->create($this->generateUrl("admin_api_teach_course_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/teach/course/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/course/addDo", name="admin_api_teach_course_add")
     */
    public function addDoAction(Request $request, CourseService $courseService){
        $name = $request->get("name");
        $type = (int) $request->get("type");
        $bigImg= $request->get("bigImg");
        $descr = $request->get("descr");
        $categoryId = (int) $request->get("categoryId");
        $schoolId = (int) $request->get("schoolId");
        $courseHour = (int) $request->get("courseHour");

        if(!$name) return $this->responseError("课程名称不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("课程名称不能大于50字!");
        if($categoryId <=0) return $this->responseError("请选择分类!");
        if(!$courseHour) return $this->responseError("课时不能为空!");
        $uid = $this->getUid();
        $courseService->add($uid, $name, $type, $bigImg, $descr, $categoryId, $schoolId, $courseHour);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_teach_course_index"));
    }

    /**
     * @Rest\Get("/teach/course/edit/{id}", name="admin_teach_course_edit")
     */
    public function editAction($id, Form $form, CourseService $courseService){
        $info = $courseService->getById($id);

        $form->setFormField("课程名称", 'text', 'name' ,1);
        $form->setFormField("类型", 'select', 'type' ,1, "", function(){
            return ["线上"=>1, "线下"=>2, "混合"=>3];
        });

        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_course"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024*2;//2m
        $options["data-required"] = 0;

        $form->setFormAdvanceField("封面图", 'file', 'bigImg' ,$options);
        $form->setFormField("类目", 'select', 'categoryId', 1, "", function() use($categoryService){
            $rs = $categoryService->categorySelect();
            return $rs;
        });
        $form->setFormField("课时", 'text', 'courseHour' ,1, "","", "课时为整数");
        $form->setFormField("上课校区", 'select', 'schoolId', 0, "", function() use($courseService){
            return $courseService->getSchools();
        });
        $form->setFormField("简介", 'textarea', 'descr');
        

        $formData = $form->create($this->generateUrl("admin_api_teach_course_edit", ['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/teach/course/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/course/editDo/{id}", name="admin_api_teach_course_edit")
     */
    public function editDoAction($id, Request $request, CourseService $courseService){
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId= (int) $request->get("parentId");
        $isShow = $request->get("isShow");
        $isShow = $isShow=="on"?1:0;

        if(!$name) return $this->responseError("课程名称不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("课程名称不能大于30字!");

        $courseService->edit($id, $parentId, $name, $sort, $isShow);

        return $this->responseSuccess("编辑成功!", $this->generateUrl("admin_teach_course_index"));
    }

    /**
     * @Rest\Post("/api/teach/course/deleteDo/{id}", name="admin_api_teach_course_delete")
     */
    public function deleteDoAction($id, CourseService $courseService){
        if($courseService->hasChild($id)) return $this->responseError("删除失败，请先删除子分类!");
        $courseService->del($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_teach_course_index"));
    }

    /**
     * @Rest\Get("/api/teach/course/searchUserDo", name="admin_api_teach_course_searchUserDo")
     */
    public function searchUserDoAction(Request $request, CourseService $courseService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $courseService->searchAdminFullName($kw);
        return $data;
    }

    /**
     * @Rest\Post("/api/teach/course/switchStatusDo/{id}", name="admin_api_teach_course_switchStatus")
     */
    public function switchLockAction($id, CourseService $courseService, Request $request){
        $state = (int) $request->get("state");
        $courseService->switchStatus($id, $state);
        return $this->responseSuccess("操作成功!");
    }

}
