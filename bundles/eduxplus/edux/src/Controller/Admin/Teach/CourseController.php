<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 09:57
 */

namespace Eduxplus\EduxBundle\Controller\Admin\Teach;

use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\EduxBundle\Service\Teach\CourseService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class CourseController extends BaseAdminController
{

    
    public function indexAction(Request $request, Grid $grid, CourseService $courseService, UserService  $userService, CategoryService $categoryService)
    {

        $pageSize = 40;
        $grid->setListService($courseService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("课程名称")->field("name")->sort("a.name");
        $grid->text("类型")->field("type")->sort("a.type")->options([1 => "线上", 2 => "线下", 3 => "混合"]);
        $grid->text("品类")->field("brand");
        $grid->text("类目")->field("category");
        $grid->image("封面图")->field("bigImg");
        $grid->text("创建人")->field("creater");
        $grid->boole2("上架？")->field("status")->actionCall("admin_api_teach_course_switchStatus", function ($obj) use($courseService) {
            $id = $courseService->getPro($obj, "id");
            $defaultValue = $courseService->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_teach_course_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });

        $grid->text("课时")->field("courseHourView");
        $grid->text("上课校区")->field("school");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");


        $grid->setTableAction('admin_teach_chapter_index', function ($obj) use($courseService){
            $id = $courseService->getPro($obj, "id");
            $url = $this->generateUrl('admin_teach_chapter_index', ['id' => $id]);
            $str = '<a href="javascript:;" data-url=' . $url . ' title="章节"  class=" btn btn-info btn-xs newTab"><i class="mdi mdi-book"></i>章节</a>';
            return  $str;
        });

        $grid->setTableAction('admin_teach_course_edit', function ($obj) use($courseService) {
            $id = $courseService->getPro($obj, "id");
            $url = $this->generateUrl('admin_teach_course_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_teach_course_delete', function ($obj) use($courseService)  {
            $id = $courseService->getPro($obj, "id");
            $url = $this->generateUrl('admin_api_teach_course_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });
        //批量删除
        $delUrl = $this->genUrl("admin_api_teach_course_bathdelete");
        $grid->setBathDelete("admin_api_teach_course_bathdelete", $delUrl);

        $grid->gbAddButton("admin_teach_course_add");

        //搜索
        $select = $categoryService->categorySelect();

        $grid->snumber("ID")->field("a.id");
        $grid->stext("课程名称")->field("a.name");
        $grid->sselect("类型")->field("a.type")->options(["全部" => -1, "线上" => 1, "线下" => 2, "混合" => 3]);
        $grid->sselect("上架？")->field("a.status")->options(["全部" => -1, "下架" => 0, "上架" => 1]);
        $grid->sselect("校区")->field("a.schoolId")->options($courseService->getSchools());
        $grid->ssearchselect("创建人")->field("a.createUid")->options(function () use ($request, $userService) {
            $values = $request->get("values");
            $createUid = ($values && isset($values["a.createUid"])) ? $values["a.createUid"] : 0;
            if ($createUid) {
                $users = $userService->searchResult($createUid);
            } else {
                $users = [];
            }
            return [$this->generateUrl("admin_api_glob_searchAdminUserDo"), $users];
        });

        $grid->sselect("类别")->field("a.categoryId")->options($select);
        $grid->sdaterange("创建时间")->field("a.createdAt");
        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    
    public function addAction(Form $form, CourseService $courseService, CategoryService $categoryService)
    {
        $form->text("课程名称")->field("name")->isRequire(1);
        $form->select("类型")->field("type")->isRequire(1)->options( ["线上" => 1, "线下" => 2, "混合" => 3]);


        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "img_course"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024 * 5; //5m
        $options["data-required"] = 0;
        $form->file("封面图")->field("bigImg")->attr($options);

        $form->select("类目")->field("categoryId")->isRequire(1)->options($categoryService->categorySelect());
        $form->text("课时")->field("courseHour")->isRequire(1)->placeholder("课时为整数");
        $form->select("上课校区")->field("schoolId")->isRequire(1)->options($courseService->getSchools());
        $form->textarea("简介")->field("descr");

        $formData = $form->create($this->generateUrl("admin_api_teach_course_add"));
        return $this->content()->title("添加课程")
               ->breadcrumb("课程管理", "admin_teach_course_index")
                ->renderAdd($formData);
    }

    
    public function addDoAction(Request $request, CourseService $courseService)
    {
        $name = $request->get("name");
        $type = (int) $request->get("type");
        $bigImg = $request->get("bigImg");
        $descr = $request->get("descr");
        $categoryId = (int) $request->get("categoryId");
        $schoolId = (int) $request->get("schoolId");
        $courseHour = (int) $request->get("courseHour");

        if (!$name) return $this->responseError("课程名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("课程名称不能大于50字!");
        if ($categoryId <= 0) return $this->responseError("请选择分类!");
        if (!$courseHour) return $this->responseError("课时不能为空!");
        if (!$schoolId) return $this->responseError("校区不能为空!");

        if ($descr && (mb_strlen($descr, 'utf-8') > 450)) return $this->responseError("课程简介不能大于450字!");

        $uid = $this->getUid();
        $courseService->add($uid, $name, $type, $bigImg, $descr, $categoryId, $schoolId, $courseHour);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_teach_course_index"));
    }

    
    public function editAction($id, Form $form, CourseService $courseService,  CategoryService $categoryService)
    {
        $info = $courseService->getById($id);

        $form->text("课程名称")->field("name")->isRequire(1)->defaultValue($info['name']);
        $form->select("类型")->field("type")->isRequire(1)->options( ["线上" => 1, "线下" => 2, "混合" => 3])->defaultValue($info['type']);


        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "img_course"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024 * 5; //2m
        $options["data-required"] = 0;
        $options['data-initial-preview'] = $info["bigImg"];
        $options['data-initial-preview-config'] = $categoryService->getInitialPreviewConfig($info['bigImg']);
        $form->file("封面图")->field("bigImg")->attr($options)->defaultValue($info['bigImg']);

        $form->select("类目")->field("categoryId")->isRequire(1)->options($categoryService->categorySelect())->defaultValue($info['categoryId']);
        $form->text("课时")->field("courseHour")->isRequire(1)->placeholder("课时为整数")->defaultValue($info['courseHour'] / 100);
        $form->select("上课校区")->field("schoolId")->isRequire(1)->options($courseService->getSchools())->defaultValue($info['schoolId']);
        $form->textarea("简介")->field("descr")->defaultValue($info['descr']);

        $formData = $form->create($this->generateUrl("admin_api_teach_course_edit", ['id' => $id]));
        return $this->content()->renderEdit($formData);
    }

    
    public function editDoAction($id, Request $request, CourseService $courseService)
    {
        $name = $request->get("name");
        $type = (int) $request->get("type");
        $bigImg = $request->get("bigImg");
        $descr = $request->get("descr");
        $categoryId = (int) $request->get("categoryId");
        $schoolId = (int) $request->get("schoolId");
        $courseHour = (int) $request->get("courseHour");

        if (!$name) return $this->responseError("课程名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("课程名称不能大于50字!");
        if ($categoryId <= 0) return $this->responseError("请选择分类!");
        if (!$courseHour) return $this->responseError("课时不能为空!");
        $courseService->edit($id, $name, $type, $bigImg, $descr, $categoryId, $schoolId, $courseHour);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_teach_course_index"));
    }

    
    public function deleteDoAction($id, CourseService $courseService)
    {
        if ($courseService->hasChapter($id)) return $this->responseError("删除失败，请先删除子章节!");
        $courseService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_course_index"));
    }

    
    public function bathDeleteDoAction(Request $request, CourseService $courseService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                if ($courseService->hasChapter($id)) return $this->responseError("删除失败，请先删除子章节!");
                $courseService->del($id);
            }
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_course_index"));
    }

    
    public function switchStatusAction($id, CourseService $courseService, Request $request)
    {
        $state = $request->get("state");
        $courseService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }
}
