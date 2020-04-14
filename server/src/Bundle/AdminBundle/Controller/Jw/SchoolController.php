<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 21:51
 */

namespace App\Bundle\AdminBundle\Controller\Jw;


use App\Bundle\AdminBundle\Service\Jw\SchoolService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class SchoolController extends BaseAdminController
{
    /**
     * @Rest\Get("/jw/school/index", name="admin_jw_school_index")
     */
    public function indexAction(Request $request, Grid $grid, SchoolService $schoolService){
        $pageSize = 20;
        $grid->setListService($schoolService, "getList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("名称", "text", "name");
        $grid->setTableColumn("地址", "text", "address");
        $grid->setTableColumn("省市", "text", "stateCity");
        $grid->setTableColumn("联系方式", "text", "linkin");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        $grid->setGridBar("admin_jw_school_add","添加", $this->generateUrl("admin_jw_school_add"), "fas fa-plus", "btn-success");

        $grid->setSearchField("名称", "text", "a.name");
        $grid->setSearchField("创建时间", "daterange", "a.createdAt");

        //编辑等
        $grid->setTableAction('admin_jw_school_edit', function($obj){
            $id = $obj["id"];
            $url = $this->generateUrl('admin_jw_school_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-width="800px" data-height="600px" data-title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_jw_school_delete', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('admin_api_jw_school_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?"  class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/jw/school/index.html.twig", $data);

    }

    /**
     * @Rest\Get("/jw/school/add", name="admin_jw_school_add")
     */
    public function addAction(Form $form, SchoolService $schoolService){
        $form->setFormField("名称", 'text', 'name' ,1);
        $form->setFormField("描述", 'rich_editor', 'descr' ,0,'','','',['data-width'=>800, 'data-height'=>200]);
        $form->setFormField("地址", "text", "address", 1);
        $form->setFormField("联系方式", "text", "linkin", 1);
        $form->disableSubmit();
        $formData = $form->create($this->generateUrl("admin_api_jw_school_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/jw/school/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/jw/school/addDo", name="admin_api_jw_school_add")
     */
    public function addDoAction(Request $request, SchoolService $schoolService){
        $name = $request->get("name");
        $descr = $request->get("descr");
        $address = $request->get("address");
        $linkin = $request->get("linkin");
        $state = $request->get("state");
        $region = $request->get("region");
        $city = $request->get("city");

        if(!$name) return $this->responseError("名称不能为空!");
        if(!$address) return $this->responseError("地址不能为空!");
        if(!$state||!$city||!$region) return $this->responseError("省市不能为空!");
        if(!$linkin) return $this->responseError("联系方式不能为空!");

        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("名称不能大于30字!");
        if($schoolService->getByName($name)) return $this->responseError("名称已存在!");


        $schoolService->add($name, $descr, $address, $linkin, $state, $city, $region);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_jw_school_index"));
    }

    /**
     * @Rest\Get("/jw/school/edit/{id}", name="admin_jw_school_edit")
     */
    public function editAction($id, Form $form, SchoolService $schoolService){
        $info = $schoolService->getById($id);
        $form->setFormField("名称", 'text', 'name' ,1, $info['name']);
        $form->setFormField("描述", 'rich_editor', 'descr' ,0,$info['descr'],'','',['data-width'=>800, 'data-height'=>200]);
        $form->setFormField("地址", "text", "address", 1, $info['address']);
        $form->setFormField("联系方式", "text", "linkin", 1, $info['linkin']);
        $form->disableSubmit();
        $formData = $form->create($this->generateUrl("admin_api_jw_school_edit", ['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        $data['info'] = $info;
        return $this->render("@AdminBundle/jw/school/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/jw/school/editDo/{id}", name="admin_api_jw_school_edit")
     */
    public function editDoAction($id, Request $request, SchoolService $schoolService){
        $name = $request->get("name");
        $descr = $request->get("descr");
        $address = $request->get("address");
        $linkin = $request->get("linkin");
        $state = $request->get("state");
        $city = $request->get("city");
        $region = $request->get("region");

        if(!$name) return $this->responseError("名称不能为空!");
        if(!$address) return $this->responseError("地址不能为空!");
        if(!$state||!$city||!$region) return $this->responseError("省市不能为空!");
        if(!$linkin) return $this->responseError("联系方式不能为空!");

        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("名称不能大于30字!");
        if($schoolService->getByName($name, $id)) return $this->responseError("名称已存在!");

        $schoolService->edit($id, $name, $descr, $address, $linkin, $state, $city, $region);

        return $this->responseSuccess("编辑成功!", $this->generateUrl("admin_jw_school_index"));
    }

    /**
     * @Rest\Post("/api/jw/school/deleteDO/{id}", name="admin_api_jw_school_delete")
     */
    public function deleteAction($id, SchoolService $schoolService){
        $schoolService->del($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_jw_school_index"));
    }
}
