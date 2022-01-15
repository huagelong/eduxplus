<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/7 11:01
 */

namespace Eduxplus\CmsBundle\Controller\Admin;


use Eduxplus\CmsBundle\Service\NewsCategoryService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class NewsCategoryController extends BaseAdminController
{

    
    public function indexAction(Form $form, NewsCategoryService $newsCategoryService){
        $select = $newsCategoryService->categorySelect();

        $data = [];
        $form->text("名称")->field("name")->isRequire();

        $form->select("父节点")->field("parentId")->isRequire()->options($select);
        $form->text("排序")->field("sort")->isRequire()->defaultValue(0);
        $form->boole("展示？")->field("isShow")->isRequire();

        $formData = $form->create($this->generateUrl("admin_api_mall_news_category_add"));
        $data["addFormData"] = $formData;
        $data['categorys'] = $newsCategoryService->getCategoryTree(0);

        return $this->render("@CmsBundleAdmin/news/index_cate.html.twig", $data);
    }


    
    public function addDoAction(Request $request, NewsCategoryService $newsCategoryService)
    {
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId = $request->get("parentId");
        $isShow = $request->get("isShow");
        $isShow = $isShow == "on" ? 1 : 0;

        if (!$name) return $this->responseError("分类名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("分类名称不能大于30字!");
        if ($newsCategoryService->checkDeposit($parentId) > 3) return $this->responseError("分类树最大不能超过3层!");

        $newsCategoryService->add($name, $parentId, $sort, $isShow);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_mall_news_category_index"));
    }

    
    public function editAction($id, Form $form, NewsCategoryService $newsCategoryService)
    {
        $info = $newsCategoryService->getById($id);
        $select = $newsCategoryService->categorySelect();

        $form->text("名称")->field("name")->isRequire()->defaultValue($info['name']);

        $form->select("父节点")->field("parentId")->isRequire()->options($select)->defaultValue($info['parentId']);
        $form->text("排序")->field("sort")->isRequire()->defaultValue($info['sort']);
        $form->boole("展示？")->field("isShow")->isRequire()->defaultValue($info['isShow']);


        $formData = $form->create($this->generateUrl("admin_api_mall_news_category_edit", ['id' => $id]));
        return $this->content()->renderEdit($formData);
    }

    
    public function editDoAction($id, Request $request, NewsCategoryService $newsCategoryService)
    {
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $parentId = (int) $request->get("parentId");
        $isShow = $request->get("isShow");
        $isShow = $isShow == "on" ? 1 : 0;

        if (!$name) return $this->responseError("分类名称不能为空!");
        if (mb_strlen($name, 'utf-8') > 30) return $this->responseError("分类名称不能大于30字!");
        if ($newsCategoryService->checkDeposit($parentId) > 3) return $this->responseError("分类树最大不能超过3层!");

        $newsCategoryService->edit($id, $parentId, $name, $sort, $isShow);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_mall_news_category_index"));
    }

    
    public function deleteDoAction($id, NewsCategoryService $newsCategoryService)
    {
        if ($newsCategoryService->hasChild($id)) return $this->responseError("删除失败，请先删除子分类!");
        $newsCategoryService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_mall_news_category_index"));
    }

    
    public function updateSortAction(Request $request, NewsCategoryService $newsCategoryService)
    {
        $data = $request->request->all();
        $newsCategoryService->updateSort($data);
        return $this->responseMsgRedirect("更新排序成功!", $this->generateUrl("admin_mall_news_category_index"));
    }

}
