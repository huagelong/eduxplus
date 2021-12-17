<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 09:57
 */

namespace Eduxplus\CoreBundle\Controller\Teach;

use Eduxplus\CoreBundle\Service\Teach\CategoryService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;

class CategoryController extends BaseAdminController
{

    /**
     * @Rest\Get("/teach/category/index", name="admin_teach_category_index")
     */
    public function indexAction(Form $form, CategoryService $categoryService)
    {

        $select = $categoryService->categorySelect();

        $data = [];
        $form->text("名称")->field("name")->isRequire();

        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "img_category"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024 * 5; //5m
        $options["data-required"] = 0;

        $form->file("图标")->field("mobileIcon")->attr($options);

        $form->select("父节点")->field("parentId")->isRequire()->options($select);
        $form->text("排序")->field("sort")->isRequire()->defaultValue(0);
        $form->boole("展示？")->field("isShow")->isRequire();


        $formData = $form->create($this->generateUrl("admin_api_teach_category_add"));
        $data["addFormData"] = $formData;
        $data['categorys'] = $categoryService->getCategoryTree(0);
        //        dump($data['categorys']);exit;
        return $this->render("@CoreBundle/teach/category/index.html.twig", $data);
    }

    /**
     * @Rest\Post("/teach/category/add/do", name="admin_api_teach_category_add")
     */
    public function addDoAction(Request $request, CategoryService $categoryService)
    {
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId = $request->get("parentId");
        $mobileIcon = $request->get("mobileIcon");
        $isShow = $request->get("isShow");
        $isShow = $isShow == "on" ? 1 : 0;

        if (!$name) return $this->responseError("分类名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("分类名称不能大于30字!");
        if ($categoryService->checkDeposit($parentId) > 3) return $this->responseError("分类树最大不能超过3层!");

        $categoryService->add($name, $parentId, $sort, $isShow, $mobileIcon);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_teach_category_index"));
    }

    /**
     * @Rest\Get("/teach/category/edit/{id}", name="admin_teach_category_edit")
     */
    public function editAction($id, Form $form, CategoryService $categoryService)
    {
        $info = $categoryService->getById($id);
        $select = $categoryService->categorySelect();

        $form->text("名称")->field("name")->isRequire()->defaultValue($info['name']);

        $options = [];
        $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type" => "img_category"]);
        $options["data-min-file-count"] = 1;
        $options['data-max-total-file-count'] = 1;
        $options["data-max-file-size"] = 1024 * 5; //5m
        $options["data-required"] = 0;
        $options['data-initial-preview'] = $info["mobileIcon"];
        $options['data-initial-preview-config'] = $categoryService->getInitialPreviewConfig($info['mobileIcon']);

        $form->file("图标")->field("mobileIcon")->attr($options)->defaultValue($info['mobileIcon']);

        $form->select("父节点")->field("parentId")->isRequire()->options($select)->defaultValue($info['parentId']);
        $form->text("排序")->field("sort")->isRequire()->defaultValue($info['sort']);
        $form->boole("展示？")->field("isShow")->isRequire()->defaultValue($info['isShow']);


        $formData = $form->create($this->generateUrl("admin_api_teach_category_edit", ['id' => $id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@CoreBundle/teach/category/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/teach/category/edit/do/{id}", name="admin_api_teach_category_edit")
     */
    public function editDoAction($id, Request $request, CategoryService $categoryService)
    {
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId = (int) $request->get("parentId");
        $isShow = $request->get("isShow");
        $mobileIcon = $request->get("mobileIcon");
        $isShow = $isShow == "on" ? 1 : 0;

        if (!$name) return $this->responseError("分类名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("分类名称不能大于30字!");
        if ($categoryService->checkDeposit($parentId) > 3) return $this->responseError("分类树最大不能超过3层!");

        $categoryService->edit($id, $parentId, $name, $sort, $isShow, $mobileIcon);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_teach_category_index"));
    }

    /**
     * @Rest\Post("/teach/category/delete/do/{id}", name="admin_api_teach_category_delete")
     */
    public function deleteDoAction($id, CategoryService $categoryService)
    {
        if ($categoryService->hasChild($id)) return $this->responseError("删除失败，请先删除子分类!");
        $categoryService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_category_index"));
    }

    /**
     * @Rest\Post("/teach/category/updateSort/do", name="admin_api_teach_category_updateSort")
     */
    public function updateSortAction(Request $request, CategoryService $categoryService)
    {
        $data = $request->request->all();
        $categoryService->updateSort($data);
        return $this->responseMsgRedirect("更新排序成功!", $this->generateUrl("admin_teach_category_index"));
    }
}
