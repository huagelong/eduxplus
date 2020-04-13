<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 09:57
 */

namespace App\Bundle\AdminBundle\Controller\Teach;

use App\Bundle\AdminBundle\Service\Teach\BrandService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class BrandController extends BaseAdminController
{

    /**
     * @Rest\Get("/teach/brand/index", name="admin_teach_brand_index")
     */
    public function indexAction(Request $request, Grid $grid, BrandService $brandService){
        $pageSize = 20;
        $grid->setListService($brandService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("名称", "text", "name");
        $grid->setTableColumn("是否展示", "boole", "isShow");
        $grid->setTableColumn("排序", "text", "sort", "a.sort");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        $grid->setGridBar("admin_teach_brand_add","添加", $this->generateUrl("admin_teach_brand_add"), "fas fa-plus", "btn-success");

        $grid->setSearchField("名称", "text", "a.name");
        $grid->setSearchField("创建时间", "daterange", "a.createdAt");

        //编辑等
        $grid->setTableAction('admin_teach_brand_edit', function($obj){
            $id = $obj->getId();
            $url = $this->generateUrl('admin_teach_brand_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_teach_brand_delete', function ($obj) {
            $id = $obj->getId();
            $url = $this->generateUrl('admin_api_teach_brand_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?"  class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/teach/brand/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/teach/brand/add", name="admin_teach_brand_add")
     */
    public function addAction(Form $form, BrandService $brandService){
        $form->setFormField("名称", 'text', 'name' ,1);
        $form->setFormField("排序", 'text', 'sort' ,1);
        $form->setFormField("是否展示", 'boole', 'isShow', 1);

        $formData = $form->create($this->generateUrl("admin_api_teach_brand_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/teach/brand/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/brand/addDo", name="admin_api_teach_brand_add")
     */
    public function addDoAction(Request $request, BrandService $brandService){
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $isShow = $request->get("isShow");
        $isShow = $isShow=="on"?1:0;

        if(!$name) return $this->responseError("品类名称不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("品类名称不能大于30字!");
        if($brandService->getByName($name)) return $this->responseError("品类名称已存在!");

        $brandService->add($name, $sort, $isShow);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_teach_brand_index"));
    }

    /**
     * @Rest\Get("/teach/brand/edit/{id}", name="admin_teach_brand_edit")
     */
    public function editAction($id, Form $form, BrandService $brandService){
        $info = $brandService->getById($id);
        $form->setFormField("名称", 'text', 'name' ,1, $info['name']);
        $form->setFormField("排序", 'text', 'content' ,1, $info['sort']);
        $form->setFormField("是否展示", 'boole', 'isShow', 1, $info['isShow']);

        $formData = $form->create($this->generateUrl("admin_api_teach_brand_edit", ['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/teach/brand/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/brand/editDo/{id}", name="admin_api_teach_brand_edit")
     */
    public function editDoAction($id, Request $request, BrandService $brandService){
        $name = $request->get("name");
        $sort = (int) $request->get("sort");
        $isShow = $request->get("isShow");
        $isShow = $isShow=="on"?1:0;

        if(!$name) return $this->responseError("品类名称不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("品类名称不能大于30字!");
        if($brandService->getByName($name, $id)) return $this->responseError("品类名称已存在!");

        $brandService->edit($id, $name, $sort, $isShow);

        return $this->responseSuccess("编辑成功!", $this->generateUrl("admin_teach_brand_index"));
    }

    /**
     * @Rest\Post("/api/teach/brand/deleteDo/{id}", name="admin_api_teach_brand_delete")
     */
    public function deleteDoAction($id, BrandService $brandService){
        $brandService->del($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_teach_brand_index"));
    }

}
