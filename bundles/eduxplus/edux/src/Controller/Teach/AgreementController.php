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

    
    public function indexAction(Request $request, Grid $grid, AgreementService $agreementService){
        $pageSize = 40;
        $grid->setListService($agreementService, "agreementList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("名称")->field("name");
        $grid->boole("展示？")->field("isShow");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbAddButton("admin_teach_agreement_add");

        $grid->stext("名称")->field("a.name");
        $grid->sdaterange("创建时间")->field("a.createdAt");


        //编辑等
        $grid->viewAction("admin_teach_agreement_view")
            ->editAction("admin_teach_agreement_edit")
            ->deleteAction("admin_api_teach_agreement_delete");


        //批量删除
        $grid->setBathDelete("admin_api_teach_agreement_bathdelete");
        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    
    public function viewAction($id, View $view, AgreementService $agreementService){
        $info = $agreementService->getById($id);

        $view->text("名称")->field("name")->defaultValue($info['name']);
        $view->richEditor("内容")->field("content")->attr(['data-width'=>800, 'data-height'=>400])->defaultValue($info['content']);
        $view->boole("展示？")->field("isShow")->defaultValue($info['isShow']);

        $formData = $view->create();
        return $this->content()->renderView($formData);
    }

    
    public function addAction(Form $form, AgreementService $agreementService){
        $form->text("名称")->field("name")->isRequire();
        $form->richEditor("内容")->field("content")->isRequire()->attr(['data-width'=>800, 'data-height'=>400]);
        $form->boole("展示？")->field("isShow")->isRequire();

        $formData = $form->create($this->generateUrl("admin_api_teach_agreement_add"));
        return $this->content()->title("添加协议")->breadcrumb("协议管理", "admin_teach_agreement_index")->renderAdd($formData);
    }

    
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

    
    public function editAction($id, Form $form, AgreementService $agreementService){
        $info = $agreementService->getById($id);

        $form->text("名称")->field("name")->isRequire()->defaultValue($info['name']);
        $form->richEditor("内容")->field("content")->isRequire()->attr(['data-width'=>800, 'data-height'=>400])->defaultValue($info['content']);
        $form->boole("展示？")->field("isShow")->isRequire()->defaultValue($info['isShow']);

        $formData = $form->create($this->generateUrl("admin_api_teach_agreement_edit", ['id'=>$id]));
        return $this->content()->renderEdit($formData);
    }

    
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

    
    public function deleteAction($id, AgreementService $agreementService){
        $agreementService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_teach_agreement_index"));
    }

    
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
