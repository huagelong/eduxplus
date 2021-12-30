<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/12 15:47
 */

namespace Eduxplus\EduxBundle\Controller\Teach;


use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\EduxBundle\Service\Teach\AgreementService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class AgreementController extends BaseAdminController
{

    /**
     * @Route("/teach/agreement/index", name="admin_teach_agreement_index")
     */
    public function indexAction(Request $request, Grid $grid, AgreementService $agreementService){
        $pageSize = 40;
        $grid->setListService($agreementService, "agreementList");
        $grid->text("#")->field("id")->sort("a.id");
        $grid->text("名称")->field("name");
        $grid->boole("展示？")->field("isShow");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbButton("添加")->route("admin_teach_agreement_add")
            ->url($this->generateUrl("admin_teach_agreement_add"))
            ->styleClass("btn-success")->iconClass("fas fa-plus");

        $grid->stext("名称")->field("a.name");
        $grid->sdaterange("创建时间")->field("a.createdAt");


        //编辑等
        $grid->setTableAction('admin_teach_agreement_view', function ($obj) {
            $id = $obj->getId();
            $url = $this->generateUrl('admin_teach_agreement_view', ['id' => $id]);
            $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="fas fa-eye"></i></a>';
            return  $str;
        });

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

        //批量删除
        $bathDelUrl = $this->genUrl("admin_api_teach_agreement_bathdelete");
        $grid->setBathDelete("admin_api_teach_agreement_bathdelete", $bathDelUrl);

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@EduxBundle/teach/agreement/index.html.twig", $data);

    }

    /**
     * @Route("/teach/agreement/view/{id}", name="admin_teach_agreement_view")
     */
    public function viewAction($id, View $view, AgreementService $agreementService){
        $info = $agreementService->getById($id);

        $view->text("名称")->field("name")->defaultValue($info['name']);
        $view->richEditor("内容")->field("content")->attr(['data-width'=>800, 'data-height'=>400])->defaultValue($info['content']);
        $view->boole("展示？")->field("isShow")->defaultValue($info['isShow']);

        $formData = $view->create();
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@EduxBundle/teach/agreement/view.html.twig", $data);
    }

    /**
     * @Route("/teach/agreement/add", name="admin_teach_agreement_add")
     */
    public function addAction(Form $form, AgreementService $agreementService){
        $form->text("名称")->field("name")->isRequire();
        $form->richEditor("内容")->field("content")->isRequire()->attr(['data-width'=>800, 'data-height'=>400]);
        $form->boole("展示？")->field("isShow")->isRequire();

        $formData = $form->create($this->generateUrl("admin_api_teach_agreement_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@EduxBundle/teach/agreement/add.html.twig", $data);
    }

    /**
     * @Route("/teach/agreement/add/do", name="admin_api_teach_agreement_add")
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

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_teach_agreement_index"));
    }

    /**
     * @Route("/teach/agreement/edit/{id}", name="admin_teach_agreement_edit")
     */
    public function editAction($id, Form $form, AgreementService $agreementService){
        $info = $agreementService->getById($id);

        $form->text("名称")->field("name")->isRequire()->defaultValue($info['name']);
        $form->richEditor("内容")->field("content")->isRequire()->attr(['data-width'=>800, 'data-height'=>400])->defaultValue($info['content']);
        $form->boole("展示？")->field("isShow")->isRequire()->defaultValue($info['isShow']);

        $formData = $form->create($this->generateUrl("admin_api_teach_agreement_edit", ['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@EduxBundle/teach/agreement/edit.html.twig", $data);
    }

    /**
     * @Route("/teach/agreement/edit/do/{id}", name="admin_api_teach_agreement_edit")
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

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_teach_agreement_index"));
    }

    /**
     * @Route("/teach/agreement/delete/do/{id}", name="admin_api_teach_agreement_delete")
     */
    public function deleteAction($id, AgreementService $agreementService){
        $agreementService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_agreement_index"));
    }

    /**
     * @Route("/teach/agreement/bathdelete/do", name="admin_api_teach_agreement_bathdelete")
     */
    public function bathDeleteAction(Request $request, AgreementService $agreementService){

        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $agreementService->del($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_agreement_index"));
    }


}
