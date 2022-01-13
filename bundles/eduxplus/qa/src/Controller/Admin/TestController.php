<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/12/6 19:21
 */

namespace Eduxplus\QaBundle\Controller\Admin;


use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\QaBundle\Service\Admin\QATestService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TestController extends BaseAdminController
{

    
    public function indexAction(Request $request, Grid $grid, QATestService $testService, UserService $userService, CategoryService $categoryService){
        $pageSize = 40;
        $grid->setListService($testService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("试卷名称")->field("name");
        $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("qa_admin_test_switchStatus", function ($obj) use ($testService) {
            $id = $testService->getPro($obj, "id");
            $defaultValue = $testService->getPro($obj, "status");
            $url = $this->generateUrl('qa_admin_test_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->text("类别")->field("category")->sort("a.categoryId");
        $grid->text("考试时长")->field("expireTime");
        $grid->text("总分数")->field("score");
        $grid->text("排序")->field("sort")->sort("a.sort");
        $grid->text("创建人")->field("creater");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbAddButton("qa_admin_test_add");
        $grid->setTableAction('qa_admin_test_preview', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_test_preview', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="试卷预览" title="试卷预览" class=" btn btn-info btn-xs"><i class="mdi mdi-file-eye"></i></a>';
            return  $str;
        });

        $grid->setTableAction('qa_admin_test_sub_index', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_test_sub_index', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="考题管理" title="考题管理" class=" btn btn-info btn-xs"><i class="mdi mdi-plus-square"></i></a>';
            return  $str;
        });

        $grid->setTableAction('qa_admin_test_edit', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_test_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('qa_admin_test_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_test_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });

        //批量删除
        $grid->setBathDelete("qa_admin_test_bathdelete");

        $grid->snumber("ID")->field("a.id");
        $grid->stext("试卷名称")->field("a.name");
        $grid->sselect("上架?")->field("a.status")->options(["全部" => -1, "下架" => 0, "上架" => 1]);
        $select = $categoryService->categorySelect();
        $grid->sselect("类别")->field("a.categoryId")->options(function () use ($select) {
            return $select;
        });

        $grid->sdaterange("创建时间")->field("a.createdAt");
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



        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@QaBundleAdmin/test/index.html.twig", $data);
    }

    
    public function previewAction(Form $form, QATestService $testService){

    }

    
    public function addAction(Form $form, CategoryService $categoryService){
        $form->text("试卷名称")->field("name")->isRequire();
        $form->select("类目")->field("categoryId")->isRequire()->options($categoryService->categorySelect());
        $form->boole("上架？")->field("status")->isRequire(1);
        $form->text("考试时长")->field("expireTime")->isRequire(1)->placeholder("单位分钟")->defaultValue(60);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue(0);

        $formData = $form->create($this->generateUrl("qa_admin_test_do_add"));
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@QaBundleAdmin/test/add.html.twig", $data);
    }

    
    public function addDoAction(Request $request, QATestService $testService){
        $name = $request->get("name");
        $categoryId = $request->get("categoryId");
        $status = $request->get("status");
        $sort = (int) $request->get("sort");
        $expireTime = (int) $request->get("expireTime");
        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("试卷名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("试卷名称不能大于50字!");
        if ($testService->checkName($name)) return $this->responseError("试卷名称已存在!");
        if($expireTime<=0) return  $this->responseError("考试时长不能不小0分钟!");
        if(!$categoryId){
            return $this->responseError("类目必须选择!");
        }
        $uid= $this->getUid();
        $testService->add($uid, $name, $categoryId, $sort,$status, $expireTime);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('qa_admin_test_index'));
    }

    
    public function editAction($id, Form $form, QATestService $testService, CategoryService $categoryService){
        $info = $testService->getById($id);

        $form->text("试卷名称")->field("name")->isRequire()->defaultValue($info["name"]);
        $form->select("类目")->field("categoryId")->isRequire()->options($categoryService->categorySelect())->defaultValue($info['categoryId']);
        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);
        $form->text("排序")->field("sort")->isRequire(1)->defaultValue($info['sort']);
        $form->text("考试时长")->field("expireTime")->isRequire(1)->defaultValue($info['expireTime'])->placeholder("单位分钟");
        $formData = $form->create($this->generateUrl("qa_admin_test_do_edit", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@QaBundleAdmin/test/edit.html.twig", $data);
    }

    
    public function editDoAction($id, Request $request, QATestService $testService){
        $name = $request->get("name");
        $categoryId = $request->get("categoryId");
        $status = $request->get("status");
        $sort = (int) $request->get("sort");
        $expireTime = (int) $request->get("expireTime");
        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("试卷名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("试卷名称不能大于50字!");
        if ($testService->checkName($name, $id)) return $this->responseError("试卷名称已存在!");
        if($expireTime<=0) return  $this->responseError("考试时长不能不小0分钟!");
        if(!$categoryId){
            return $this->responseError("类目必须选择!");
        }

        $testService->edit($id, $name, $categoryId, $sort,$status, $expireTime);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('qa_admin_test_index'));
    }

    
    public function deleteDoAction($id, QATestService $testService)
    {
        $testService->del($id);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("qa_admin_test_index"));
    }

    
    public function bathdeleteDoAction(Request $request, QATestService $testService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $testService->del($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("qa_admin_test_index"));
    }

    
    public function switchStatusAction($id,Request $request, QATestService $testService)
    {
        $state = (int) $request->get("state");
        $testService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

}


