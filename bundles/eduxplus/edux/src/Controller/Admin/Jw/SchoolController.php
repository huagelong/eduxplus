<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/13 21:51
 */

namespace Eduxplus\EduxBundle\Controller\Admin\Jw;


use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\EduxBundle\Service\Jw\SchoolService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class SchoolController extends BaseAdminController
{
    
    public function indexAction(Request $request, Grid $grid, SchoolService $schoolService){
        $pageSize = 40;
        $grid->setListService($schoolService, "getList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("名称")->field("name");
        $grid->text("地址")->field("address");
        $grid->text("省市")->field("stateCity");
        $grid->text("联系方式")->field("linkin");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");
        $grid->stext("名称")->field("a.name");
        $grid->sdaterange("创建时间")->field("a.createdAt");

        $grid->gbAddButton("admin_jw_school_add");
        //编辑等
        $grid->viewAction("admin_jw_school_view")
            ->editAction("admin_jw_school_edit")
            ->deleteAction("admin_api_jw_school_delete");

        //批量删除
        $grid->setBathDelete("admin_api_jw_school_bathdelete");
        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    
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
        return $this->render("@EduxBundle/jw/school/view.html.twig", $data);
    }

    
    public function addAction(Form $form, SchoolService $schoolService){
        $form->text("名称")->field("name")->isRequire(1);
        $form->richEditor("描述")->field("descr")->attr(['data-width'=>800, 'data-height'=>200]);
        $form->text("地址")->field("address")->isRequire(1);
        $form->text("联系方式")->field("linkin")->isRequire(1);
        $form->disableSubmit();
        $formData = $form->create($this->generateUrl("admin_api_jw_school_add"));
        $data = [];
        $data["formData"] = $formData;$data["breadcrumb"] = 1;
        return $this->render("@EduxBundle/jw/school/add.html.twig", $data);
    }

    
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
        return $this->render("@EduxBundle/jw/school/edit.html.twig", $data);
    }

    
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

    
    public function deleteAction($id, SchoolService $schoolService){
        $schoolService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_jw_school_index"));
    }

    
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
