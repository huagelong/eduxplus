<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/28 19:41
 */

namespace Eduxplus\QaBundle\Controller\Admin;


use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\EduxBundle\Service\Mall\NewsService;
use Eduxplus\EduxBundle\Service\Teach\CategoryService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\QaBundle\Service\Admin\QAChapterService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ChapterController extends BaseAdminController
{

    /**
     * @Route("/chapter/index", name="qa_admin_chapter_index")
     */
    public function indexAction(Request $request, Grid $grid,QAChapterService $chapterService, CategoryService $categoryService){

        $pageSize = 40;
        $grid->setListService($chapterService, "getCollectionList");
        $grid->text("#")->field("id")->sort("a.id");
        $grid->text("试题集合名称")->field("name");
        $grid->text("类目")->field("category");
        $grid->boole2("上架？")->field("status")->sort("a.status")->actionCall("qa_admin_chapter_switchStatus", function ($obj) use($chapterService) {
            $id = $chapterService->getPro($obj, "id");
            $defaultValue = $chapterService->getPro($obj, "status");
            $url = $this->generateUrl('qa_admin_chapter_switchStatus', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $confirmStr = $defaultValue ? "确认要下架吗？" : "确认要上架吗?";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"{$confirmStr}\" {$checkStr} >";
            return $str;
        });
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        //添加
        $grid->gbButton("添加")->route("qa_admin_chapter_add")
            ->url($this->generateUrl("qa_admin_chapter_add"))
            ->styleClass("btn-success")->iconClass("mdi mdi-plus");

        //搜索
        $grid->snumber("ID")->field("a.id");
        $grid->stext("试题集合名称")->field("a.name");
        $grid->sselect("上架?")->field("a.status")->options(["全部" => -1, "下架" => 0, "上架" => 1]);
        $select = $categoryService->categorySelect();
        $grid->sselect("类别")->field("a.categoryId")->options(function () use ($select) {
            return $select;
        });
        $grid->sdaterange("创建时间")->field("a.createdAt");


        $grid->setTableAction('qa_admin_node_index', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_node_index', ['chapterId' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="题目管理" title="题目管理" class=" btn btn-info btn-xs"><i class="fab fa-node"></i></a>';
            return  $str;
        });

        $grid->setTableAction('qa_admin_chaptersub_index', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_chaptersub_index', ['chapterId' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="章节点管理" title="章节点管理" class=" btn btn-info btn-xs"><i class="fas fa-tree"></i></a>';
            return  $str;
        });

        $grid->setTableAction('qa_admin_chapter_edit', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_chapter_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-width="1000px" data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('qa_admin_chapter_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('qa_admin_chapter_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });

        //批量删除
        $bathDelUrl = $this->genUrl("qa_admin_chapter_bathdelete");
        $grid->setBathDelete("qa_admin_chapter_bathdelete", $bathDelUrl);

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@QaBundleAdmin/chapter/index.html.twig", $data);
    }


    /**
     * @Route("/chapter/add", name="qa_admin_chapter_add")
     */
    public function addAction(Form $form, CategoryService $categoryService){
        $form->text("集合名称")->field("name")->isRequire();
        $form->select("类目")->field("categoryId")->isRequire()->options($categoryService->categorySelect());
        $form->boole("上架？")->field("status")->isRequire(1);

        $formData = $form->create($this->generateUrl("qa_admin_chapter_do_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@QaBundleAdmin/chapter/add.html.twig", $data);
    }

    /**
     *
     * @Route("/chapter/add/do", name="qa_admin_chapter_do_add")
     */
    public function addDoAction(Request $request, QAChapterService $chapterService)
    {
        $name = $request->get("name");
        $categoryId = $request->get("categoryId");
        $status = $request->get("status");
        $status = $status == "on" ? 1 : 0;


        if (!$name) return $this->responseError("集合名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("集合名称不能大于50字!");
        if ($chapterService->checkName($name)) return $this->responseError("集合名称已存在!");
        if(!$categoryId){
            return $this->responseError("类目必须选择!");
        }

        $chapterService->add($name, $categoryId, $status);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('qa_admin_chapter_index'));
    }


    /**
     * @Route("/chapter/edit/{id}", name="qa_admin_chapter_edit")
     */
    public function editAction($id, Form $form,CategoryService $categoryService, QAChapterService $chapterService){
        $info = $chapterService->getById($id);
        $form->text("集合名称")->field("name")->isRequire()->defaultValue($info['name']);
        $form->select("类目")->field("categoryId")->isRequire()->options($categoryService->categorySelect())->defaultValue($info['categoryId']);
        $form->boole("上架？")->field("status")->isRequire(1)->defaultValue($info['status']);
        $formData = $form->create($this->generateUrl("qa_admin_chapter_do_edit", [
            'id' => $id
        ]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@QaBundleAdmin/chapter/edit.html.twig", $data);
    }

    /**
     *
     * @Route("/chapter/edit/do/{id}", name="qa_admin_chapter_do_edit")
     */
    public function editDoAction($id, Request $request, CategoryService $categoryService, QAChapterService $chapterService)
    {
        $name = $request->get("name");
        $categoryId = $request->get("categoryId");
        $status = $request->get("status");
        $status = $status == "on" ? 1 : 0;


        if (!$name) return $this->responseError("集合名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 50) return $this->responseError("集合名称不能大于50字!");
        if ($chapterService->checkName($name, $id)) return $this->responseError("集合名称已存在!");
        if(!$categoryId){
            return $this->responseError("类目必须选择!");
        }

        $chapterService->edit($id, $name, $categoryId, $status);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('qa_admin_chapter_index'));
    }

    /**
     *
     * @Route("/chapter/delete/do/{id}", name="qa_admin_chapter_delete")
     */
    public function deleteDoAction($id, QAChapterService $chapterService)
    {
        $chapterService->del($id);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("qa_admin_chapter_index"));
    }

    /**
     *
     * @Route("/chapter/bathdelete/do", name="qa_admin_chapter_bathdelete")
     */
    public function bathdeleteDoAction(Request $request, QAChapterService $chapterService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $chapterService->del($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("qa_admin_chapter_index"));
    }

    /**
     * @Route("/chapter/switchStatus/do/{id}", name="qa_admin_chapter_switchStatus")
     */
    public function switchStatusAction($id, QAChapterService $chapterService, Request $request)
    {
        $state = (int) $request->get("state");
        $chapterService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

    /**
     * @Route("/chapter/searchChapter/do", name="qa_admin_chapter_searchChapter")
     */
    public function searchChapterAction(Request $request, QAChapterService $chapterService){
        $kw = $request->get("kw");
        $categoryId = $request->get("categoryId");
        if(!$kw) return [];
        $data = $chapterService->searchChapterByName($kw, $categoryId);
        return $data;
    }
}
