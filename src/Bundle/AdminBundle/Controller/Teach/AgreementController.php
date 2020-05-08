<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 15:47
 */

namespace App\Bundle\AdminBundle\Controller\Teach;


use App\Bundle\AdminBundle\Service\Teach\AgreementService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class AgreementController extends BaseAdminController
{

    /**
     * @Rest\Get("/teach/agreement/index", name="admin_teach_agreement_index")
     */
    public function indexAction(Request $request, Grid $grid, AgreementService $agreementService){
        $pageSize = 20;
        $grid->setListService($agreementService, "agreementList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("名称", "text", "name");
        $grid->setTableColumn("展示？", "boole", "isShow");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        $grid->setGridBar("admin_teach_agreement_add","添加", $this->generateUrl("admin_teach_agreement_add"), "fas fa-plus", "btn-success");

        $grid->setSearchField("名称", "text", "a.name");
        $grid->setSearchField("创建时间", "daterange", "a.createdAt");

        //编辑等
        $grid->setTableAction('admin_teach_agreement_edit', function($obj){
            $id = $obj->getId();
            $url = $this->generateUrl('admin_teach_agreement_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-width="1000px" data-height="600px" title="编辑" data-title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_teach_agreement_delete', function ($obj) {
            $id = $obj->getId();
            $url = $this->generateUrl('admin_api_teach_agreement_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除" class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/teach/agreement/index.html.twig", $data);

    }

    /**
     * @Rest\Get("/teach/agreement/add", name="admin_teach_agreement_add")
     */
    public function addAction(Form $form, AgreementService $agreementService){
        $form->setFormField("名称", 'text', 'name' ,1);
        $form->setFormField("内容", 'rich_editor', 'content' ,1,'','','',['data-width'=>800, 'data-height'=>400]);
        $form->setFormField("展示？", 'boole', 'isShow', 1);

        $formData = $form->create($this->generateUrl("admin_api_teach_agreement_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/teach/agreement/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/agreement/addDo", name="admin_api_teach_agreement_add")
     */
    public function addDoAction(Request $request, AgreementService $agreementService){
        $name = $request->get("name");
        $content = $request->get("content");
        $isShow = $request->get("isShow");
        $isShow = $isShow=="on"?1:0;

        if(!$name) return $this->responseError("协议名称不能为空!");
        if(!$content) return $this->responseError("协议内容不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("协议名称不能大于30字!");
        if($agreementService->getByName($name)) return $this->responseError("协议名称已存在!");

        $agreementService->add($name, $content, $isShow);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_teach_agreement_index"));
    }

    /**
     * @Rest\Get("/teach/agreement/edit/{id}", name="admin_teach_agreement_edit")
     */
    public function editAction($id, Form $form, AgreementService $agreementService){
        $info = $agreementService->getById($id);
        $form->setFormField("名称", 'text', 'name' ,1, $info['name']);
        $form->setFormField("内容", 'rich_editor', 'content' ,1,$info['content'],'','',['data-width'=>800, 'data-height'=>400]);
        $form->setFormField("展示？", 'boole', 'isShow', 1, $info['isShow']);

        $formData = $form->create($this->generateUrl("admin_api_teach_agreement_edit", ['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/teach/agreement/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/teach/agreement/editDo/{id}", name="admin_api_teach_agreement_edit")
     */
    public function editDoAction($id, Request $request, AgreementService $agreementService){
        $name = $request->get("name");
        $content = $request->get("content");
        $isShow = $request->get("isShow");
        $isShow = $isShow=="on"?1:0;

        if(!$name) return $this->responseError("协议名称不能为空!");
        if(!$content) return $this->responseError("协议内容不能为空!");
        if(mb_strlen($name, 'utf-8')>30) return $this->responseError("协议名称不能大于30字!");
        if($agreementService->getByName($name, $id)) return $this->responseError("协议名称已存在!");

        $agreementService->edit($id, $name, $content, $isShow);

        return $this->responseSuccess("编辑成功!", $this->generateUrl("admin_teach_agreement_index"));
    }

    /**
     * @Rest\Post("/api/teach/agreement/deleteDO/{id}", name="admin_api_teach_agreement_delete")
     */
    public function deleteAction($id, AgreementService $agreementService){
        $agreementService->del($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_teach_agreement_index"));
    }
}
