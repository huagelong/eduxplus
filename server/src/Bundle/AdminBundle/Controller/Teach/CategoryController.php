<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 09:57
 */

namespace App\Bundle\AdminBundle\Controller\Teach;

use App\Bundle\AdminBundle\Service\Teach\CategoryService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;

class CategoryController extends BaseAdminController
{

    /**
     * @Rest\Get("/teach/category/index", name="admin_teach_category_index")
     */
    public function indexAction(Form $form,CategoryService $categoryService){

        $select = $categoryService->categorySelect();

        $data = [];
        $form->setFormField("名称", 'text', 'name' ,1);
        $form->setFormField("父节点", 'select', 'parentId' ,1, "", function()use($select){
            return $select;
        });
        $form->setFormField("排序", 'text', 'sort' ,1, 0);
        $form->setFormField("是否展示", 'boole', 'isShow', 1);

        $formData = $form->create($this->generateUrl("admin_api_teach_category_add"));
        $data["addFormData"] = $formData;
        $data['categorys'] = $categoryService->getCategoryTree(0);
//        dump($data['categorys']);exit;
        return $this->render("@AdminBundle/teach/category/index.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/category/addDo", name="admin_api_teach_category_add")
     */
    public function addDoAction(Request $request, CategoryService $categoryService){
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId= $request->get("parentId");
        $isShow = $request->get("isShow");
        $isShow = $isShow=="on"?1:0;

        if(!$name) return $this->responseError("分类名称不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("分类名称不能大于30字!");

        $categoryService->add($name, $parentId, $sort, $isShow);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_teach_category_index"));
    }

    /**
     * @Rest\Get("/teach/category/edit/{id}", name="admin_teach_category_edit")
     */
    public function editAction($id, Form $form, CategoryService $categoryService){
        $info = $categoryService->getById($id);
        $select = $categoryService->categorySelect();

        $form->setFormField("名称", 'text', 'name' ,1,  $info['name']);
        $form->setFormField("父节点", 'select', 'parentId' ,1,  $info['parentId'], function()use($select){
            return $select;
        });
        $form->setFormField("排序", 'text', 'sort' ,1,  $info['sort']);
        $form->setFormField("是否展示", 'boole', 'isShow', 1,  $info['isShow']);


        $formData = $form->create($this->generateUrl("admin_api_teach_category_edit", ['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/teach/category/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/category/editDo/{id}", name="admin_api_teach_category_edit")
     */
    public function editDoAction($id, Request $request, CategoryService $categoryService){
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId= (int) $request->get("parentId");
        $isShow = $request->get("isShow");
        $isShow = $isShow=="on"?1:0;

        if(!$name) return $this->responseError("分类名称不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("分类名称不能大于30字!");

        $categoryService->edit($id, $parentId, $name, $sort, $isShow);

        return $this->responseSuccess("编辑成功!", $this->generateUrl("admin_teach_category_index"));
    }

    /**
     * @Rest\Post("/api/teach/category/deleteDo/{id}", name="admin_api_teach_category_delete")
     */
    public function deleteDoAction($id, CategoryService $categoryService){
        if($categoryService->hasChild($id)) return $this->responseError("删除失败，请先删除子分类!");
        $categoryService->del($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_teach_category_index"));
    }

    /**
     * @Rest\Post("/api/teach/category/updateSortDo", name="admin_api_teach_category_updateSort")
     */
    public function updateSortAction(Request $request,CategoryService $categoryService){
        $data = $request->request->all();
        $categoryService->updateSort($data);
        return $this->responseSuccess("更新排序成功!", $this->generateUrl("admin_teach_category_index"));
    }

}
