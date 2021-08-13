<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:01
 */

namespace App\Bundle\AdminBundle\Controller\Mall;


use App\Bundle\AdminBundle\Service\Mall\HelpCategoryService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class HelpCategoryController extends BaseAdminController
{

    /**
     *
     * @Rest\Get("/mall/help/category/index", name="admin_mall_help_category_index")
     */
    public function indexAction(Form $form, HelpCategoryService $helpCategoryService){
        $select = $helpCategoryService->categorySelect();

        $data = [];
        $form->text("名称")->field("name")->isRequire();

        $form->select("父节点")->field("parentId")->isRequire()->options($select);
        $form->text("排序")->field("sort")->isRequire()->defaultValue(0);
        $form->boole("展示？")->field("isShow")->isRequire();

        $formData = $form->create($this->generateUrl("admin_api_mall_help_category_add"));
        $data["addFormData"] = $formData;
        $data['categorys'] = $helpCategoryService->getCategoryTree(0);

        return $this->render("@AdminBundle/mall/help/index_cate.html.twig", $data);
    }


    /**
     * @Rest\Post("/mall/help/category/add/do", name="admin_api_mall_help_category_add")
     */
    public function addDoAction(Request $request, HelpCategoryService $helpCategoryService)
    {
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId = $request->get("parentId");
        $isShow = $request->get("isShow");
        $isShow = $isShow == "on" ? 1 : 0;

        if (!$name) return $this->responseError("分类名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("分类名称不能大于30字!");
        if ($helpCategoryService->checkDeposit($parentId) > 3) return $this->responseError("分类树最大不能超过3层!");

        $helpCategoryService->add($name, $parentId, $sort, $isShow);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_mall_help_category_index"));
    }

    /**
     * @Rest\Get("/mall/help/category/edit/{id}", name="admin_mall_help_category_edit")
     */
    public function editAction($id, Form $form, HelpCategoryService $helpCategoryService)
    {
        $info = $helpCategoryService->getById($id);
        $select = $helpCategoryService->categorySelect();

        $form->text("名称")->field("name")->isRequire()->defaultValue($info['name']);

        $form->select("父节点")->field("parentId")->isRequire()->options($select)->defaultValue($info['parentId']);
        $form->text("排序")->field("sort")->isRequire()->defaultValue($info['sort']);
        $form->boole("展示？")->field("isShow")->isRequire()->defaultValue($info['isShow']);


        $formData = $form->create($this->generateUrl("admin_api_mall_help_category_edit", ['id' => $id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/mall/help/edit_cate.html.twig", $data);
    }

    /**
     * @Rest\Post("/mall/help/category/edit/do/{id}", name="admin_api_mall_help_category_edit")
     */
    public function editDoAction($id, Request $request, HelpCategoryService $helpCategoryService)
    {
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId = (int) $request->get("parentId");
        $isShow = $request->get("isShow");
        $isShow = $isShow == "on" ? 1 : 0;

        if (!$name) return $this->responseError("分类名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("分类名称不能大于30字!");
        if ($helpCategoryService->checkDeposit($parentId) > 3) return $this->responseError("分类树最大不能超过3层!");

        $helpCategoryService->edit($id, $parentId, $name, $sort, $isShow);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_mall_help_category_index"));
    }

    /**
     * @Rest\Post("/mall/help/category/delete/do/{id}", name="admin_api_mall_help_category_delete")
     */
    public function deleteDoAction($id, HelpCategoryService $helpCategoryService)
    {
        if ($helpCategoryService->hasChild($id)) return $this->responseError("删除失败，请先删除子分类!");
        $helpCategoryService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_help_category_index"));
    }

    /**
     * @Rest\Post("/mall/help/category/updateSort/do", name="admin_api_mall_help_category_updateSort")
     */
    public function updateSortAction(Request $request, HelpCategoryService $helpCategoryService)
    {
        $data = $request->request->all();
        $helpCategoryService->updateSort($data);
        return $this->responseMsgRedirect("更新排序成功!", $this->generateUrl("admin_mall_help_category_index"));
    }

}
