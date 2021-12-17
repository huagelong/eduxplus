<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 21:51
 */

namespace Eduxplus\CoreBundle\Controller\Jw;


use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\CoreBundle\Service\Jw\SchoolService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class SchoolController extends BaseAdminController
{
    /**
     * @Rest\Get("/jw/school/index", name="admin_jw_school_index")
     */
    public function indexAction(Request $request, Grid $grid, SchoolService $schoolService){
        $pageSize = 40;
        $grid->setListService($schoolService, "getList");
        $grid->text("#")->field("id")->sort("a.id");
        $grid->text("名称")->field("name");
        $grid->text("地址")->field("address");
        $grid->text("省市")->field("stateCity");
        $grid->text("联系方式")->field("linkin");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbButton("添加")->route("admin_jw_school_add")
            ->url($this->generateUrl("admin_jw_school_add"))->styleClass("btn-success")->iconClass("fas fa-plus");

        $grid->stext("名称")->field("a.name");
        $grid->sdaterange("创建时间")->field("a.createdAt");

        //编辑等
        $grid->setTableAction('admin_jw_school_view', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('admin_jw_school_view', ['id' => $id]);
            $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="fas fa-eye"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_jw_school_edit', function($obj){
            $id = $obj["id"];
            $url = $this->generateUrl('admin_jw_school_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-width="800px" data-height="600px" title="编辑" data-title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_jw_school_delete', function ($obj) {
            $id = $obj["id"];
            $url = $this->generateUrl('admin_api_jw_school_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?"  title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        //批量删除
        $bathDelUrl = $this->genUrl("admin_api_jw_school_bathdelete");
        $grid->setBathDelete("admin_api_jw_school_bathdelete", $bathDelUrl);

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@CoreBundle/jw/school/index.html.twig", $data);

    }

    /**
     * @Rest\Get("/jw/school/view/{id}", name="admin_jw_school_view")
     */
    public function viewAction($id, View $view, SchoolService $schoolService){
        $info = $schoolService->getById($id);

        $view->text("名称")->field("name")->defaultValue($info['name']);
        $view->richEditor("描述")->field("descr")->attr(['data-width'=>800, 'data-height'=>200])->defaultValue($info['descr']);
        $view->text("地址")->field("address")->defaultValue($info['address']);
        $view->text("联系方式")->field("linkin")->defaultValue($info['linkin']);
        $view->disableSubmit();
        $formData = $view->create();
        $data = [];
        $data["formData"] = $formData;
        $data['info'] = $info;
        return $this->render("@CoreBundle/jw/school/view.html.twig", $data);
    }

    /**
     * @Rest\Get("/jw/school/add", name="admin_jw_school_add")
     */
    public function addAction(Form $form, SchoolService $schoolService){
        $form->text("名称")->field("name")->isRequire(1);
        $form->richEditor("描述")->field("descr")->attr(['data-width'=>800, 'data-height'=>200]);
        $form->text("地址")->field("address")->isRequire(1);
        $form->text("联系方式")->field("linkin")->isRequire(1);
        $form->disableSubmit();
        $formData = $form->create($this->generateUrl("admin_api_jw_school_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@CoreBundle/jw/school/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/jw/school/add/do", name="admin_api_jw_school_add")
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

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_jw_school_index"));
    }

    /**
     * @Rest\Get("/jw/school/edit/{id}", name="admin_jw_school_edit")
     */
    public function editAction($id, Form $form, SchoolService $schoolService){
        $info = $schoolService->getById($id);

        $form->text("名称")->field("name")->isRequire(1)->defaultValue($info['name']);
        $form->richEditor("描述")->field("descr")->attr(['data-width'=>800, 'data-height'=>200])->defaultValue($info['descr']);
        $form->text("地址")->field("address")->isRequire(1)->defaultValue($info['address']);
        $form->text("联系方式")->field("linkin")->isRequire(1)->defaultValue($info['linkin']);

        $form->disableSubmit();
        $formData = $form->create($this->generateUrl("admin_api_jw_school_edit", ['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        $data['info'] = $info;
        return $this->render("@CoreBundle/jw/school/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/jw/school/edit/do/{id}", name="admin_api_jw_school_edit")
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

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_jw_school_index"));
    }

    /**
     * @Rest\Post("/jw/school/delete/do/{id}", name="admin_api_jw_school_delete")
     */
    public function deleteAction($id, SchoolService $schoolService){
        $schoolService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_jw_school_index"));
    }

    /**
     * @Rest\Post("/jw/school/bathdelete/do", name="admin_api_jw_school_bathdelete")
     */
    public function bathdeleteAction(Request $request, SchoolService $schoolService){

        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $schoolService->del($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_jw_school_index"));
    }

}
