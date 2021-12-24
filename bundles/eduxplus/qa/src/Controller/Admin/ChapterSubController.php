<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/30 19:14
 */

namespace Eduxplus\QaBundle\Controller\Admin;


use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\QaBundle\Service\Admin\QAChapterService;
use Eduxplus\QaBundle\Service\Admin\QAChapterSubService;
use Symfony\Component\Routing\Annotation\Route;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ChapterSubController extends BaseAdminController
{
    /**
     * @Route("/chaptersub/index/{chapterId}", name="qa_admin_chaptersub_index")
     */
    public function indexAction($chapterId, Form $form, QAChapterSubService $chapterSubService, QAChapterService $chapterService){

        $select = $chapterSubService->chapterSelect($chapterId);

        $form->text("名称")->field("name")->isRequire();
        $form->select("父节点")->field("parentId")->isRequire()->options($select);
        $form->text("排序")->field("sort")->isRequire()->defaultValue(0);
        $form->boole("展示？")->field("status")->isRequire();

        $data = [];
        $formData = $form->create($this->generateUrl("qa_admin_chaptersub_adddo",['chapterId' => $chapterId]));
        $data["addFormData"] = $formData;
        $data['list'] = $chapterSubService->getChapterTree(0, $chapterId);
        $data['chapter'] = $chapterService->getById($chapterId);
        $data['chapterId'] = $chapterId;

        return $this->render("@QaBundleAdmin/chaptersub/index.html.twig", $data);
    }

    /**
     * @Route("/chaptersub/add/do/{chapterId}", name="qa_admin_chaptersub_adddo")
     */
    public function adddoAction($chapterId, Request $request, QAChapterSubService $chapterSubService){
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId = $request->get("parentId");
        $status = $request->get("status");
        $status = $status == "on" ? 1 : 0;

        if (!$name) return $this->responseError("名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("名称不能大于30字!");
        if ($chapterSubService->checkDeposit($parentId) > 3) return $this->responseError("章节点树最大不能超过3层!");

        $chapterSubService->add($name, $parentId, $sort, $status, $chapterId);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("qa_admin_chaptersub_index",["chapterId"=>$chapterId]));

    }

    /**
     * @Route("/chaptersub/edit/{id}", name="qa_admin_chaptersub_edit")
     */
    public function editAction($id, Form $form, QAChapterSubService $chapterSubService)
    {
        $info = $chapterSubService->getById($id);
        $select = $chapterSubService->chapterSelect($info['chapterId']);
        $form->text("名称")->field("name")->isRequire()->defaultValue($info['name']);

        $form->select("父节点")->field("parentId")->isRequire()->options($select)->defaultValue($info['parentId']);
        $form->text("排序")->field("sort")->isRequire()->defaultValue($info['sort']);
        $form->boole("展示？")->field("status")->isRequire()->defaultValue($info['status']);

        $formData = $form->create($this->generateUrl("qa_admin_chaptersub_edit_do", ['id' => $id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@QaBundleAdmin/chaptersub/edit.html.twig", $data);

    }

    /**
     * @Route("/chaptersub/edit/do/{id}", name="qa_admin_chaptersub_edit_do")
     */
    public function editDoAction($id, Request $request, QAChapterSubService $chapterSubService)
    {
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId = $request->get("parentId");
        $status = $request->get("status");
        $status = $status == "on" ? 1 : 0;

        $info = $chapterSubService->getById($id);

        if (!$name) return $this->responseError("名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("名称不能大于30字!");
        if ($chapterSubService->checkDeposit($parentId) > 3) return $this->responseError("章节点树最大不能超过3层!");

        $chapterSubService->edit($name, $parentId, $sort, $status, $id);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("qa_admin_chaptersub_index",["chapterId"=>$info['chapterId']]));
    }

    /**
     * @Route("/chaptersub/delete/do/{id}", name="qa_admin_chaptersub_delete_do")
     */
    public function deleteDoAction($id, QAChapterSubService $chapterSubService)
    {
        $info = $chapterSubService->getById($id);
        if ($chapterSubService->hasChild($id)) return $this->responseError("删除失败，请先删除子节点!");
        $chapterSubService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("qa_admin_chaptersub_index",["chapterId"=>$info['chapterId']]));
    }

    /**
     * @Route("/chaptersub/updateSort/do/{chapterId}", name="qa_admin_chaptersub_updateSort")
     */
    public function updateSortAction($chapterId, Request $request, QAChapterSubService $chapterSubService)
    {
        $data = $request->request->all();
        $chapterSubService->updateSort($data);
        return $this->responseMsgRedirect("更新排序成功!", $this->generateUrl("qa_admin_chaptersub_index", ["chapterId"=>$chapterId]));
    }

    /**
     * @Route("/chaptersub/getChapterSub/do", name="qa_admin_chaptersub_getChapterSub_do")
     */
    public function getChapterSubAction(Request $request, QAChapterSubService $chapterService)
    {
        $id = $request->get("id");
        $select = $chapterService->chapterSelect2($id);
        return $select;
    }

}
