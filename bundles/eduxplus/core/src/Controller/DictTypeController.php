<?php


namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\CoreBundle\Service\DictDataService;
use Eduxplus\CoreBundle\Service\DictTypeService;
use Symfony\Component\HttpFoundation\Request;

class DictTypeController extends BaseAdminController
{

    public function indexAction(Request $request, Grid $grid, DictTypeService $dictTypeService){
        $pageSize = 40;
        $grid->setListService($dictTypeService, "dictTypeList");
        $grid->text("字典名称")->field("dictName");
        $grid->text("字典key")->field("dictKey");
        $grid->boole2("字典状态")->field("status")->actionCall("admin_api_dict_type_switch_status", function ($obj) use($dictTypeService) {
            $id = $dictTypeService->getPro($obj, "id");
            $defaultValue = $dictTypeService->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_dict_type_switch_status', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"确认切换当前状态吗?\" {$checkStr} >";
            return $str;
        });
        $grid->text("备注")->field("descr");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbAddButton("admin_dict_type_add");
        $grid->snumber("ID")->field("a.id");
        $grid->stext("字典名称")->field("a.dictName");
        $grid->stext("字典key")->field("a.dictKey");
        $grid->sselect("字典状态？")->field("a.isAdmin")->options(function () {
            return ["全部" => -1, "开启" => 1, "关闭" => 0];
        });
        $grid->sdaterange("创建时间")->field("a.createdAt");

        //编辑等
        $grid->viewAction("admin_dict_type_view")
            ->editAction("admin_dict_type_edit")
            ->deleteAction("admin_api_dict_type_delete");

        //批量删除
        $grid->setBathDelete("admin_api_dict_type_bathdelete");

        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    public function addAction(Form $form, DictTypeService $dictTypeService)
    {
        $form->text("字典名称")->field("dictName")->isRequire(1);
        $form->text("字典key")->field("dictKey")->isRequire(1);
        $form->boole("字典状态")->field("status")->isRequire(1);
        $form->textarea("备注")->field("descr");
        $formData = $form->create($this->generateUrl("admin_api_dict_type_add"));
        return $this->content()->title("添加字典")->breadcrumb("字典管理", "admin_dict_type_index")->renderAdd($formData);
    }


    public function addDoAction(   Request $request,
                                   DictTypeService $dictTypeService)
    {
        $dictName = $request->get("dictName");
        $dictKey = $request->get("dictKey");
        $descr = $request->get("descr");
        $status = $request->get("status");
        $status = $status=="on"?1:0;
        if(!$dictName) return $this->responseError("字典名称不能为空!");
        if(!$dictKey) return $this->responseError("字典key不能为空!");
        $dictTypeService->add($dictName, $dictKey, $status, $descr);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_dict_type_index"));
    }


    public function viewAction($id,View $view, DictTypeService $dictTypeService)
    {
        $info = $dictTypeService->getById($id);
        $view->text("字典名称")->field("dictName")->defaultValue($info['dictName']);
        $view->text("字典key")->field("dictKey")->defaultValue($info['dictKey']);
        $view->boole("字典状态")->field("status")->defaultValue($info['status']);
        $view->textarea("备注")->field("descr")->defaultValue($info['descr']);
        $formData = $view->create();
        return $this->content()->renderView($formData);
    }

    public function editAction($id, Form $form, DictTypeService $dictTypeService)
    {
        $info = $dictTypeService->getById($id);
        $form->text("字典名称")->field("dictName")->isRequire(1)->defaultValue($info['dictName']);
        $form->text("字典key")->field("dictKey")->isRequire(1)->defaultValue($info['dictKey']);
        $form->boole("字典状态")->field("status")->isRequire(1)->defaultValue($info['status']);
        $form->textarea("备注")->field("descr")->defaultValue($info['descr']);
        $formData = $form->create($this->generateUrl("admin_api_dict_type_edit",['id'=>$id]));
        return $this->content()->renderEdit($formData);
    }

    public function editDoAction(  $id,
                                   Request $request,
                                   DictTypeService $dictTypeService)
    {
        $info = $dictTypeService->getById($id);
        $dictName = $request->get("dictName");
        $dictKey = $request->get("dictKey");
        $descr = $request->get("descr");
        $status = $request->get("status");
        $status = $status=="on"?1:0;
        if(!$dictName) return $this->responseError("字典名称不能为空!");
        if(!$dictKey) return $this->responseError("字典key不能为空!");
        $dictTypeService->edit($id, $dictName, $dictKey, $status, $descr);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_dict_type_index"));
    }

    public function deleteAction($id, DictTypeService $dictTypeService, DictDataService  $dictDataService){
        if($dictDataService->getByTypeId($id)){
            return $this->responseError("对应字典数据需要先删除!");
        }
        $dictTypeService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_dict_type_index"));
    }

    public function bathdeleteAction(Request $request, DictTypeService $dictTypeService, DictDataService  $dictDataService){
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                if($dictDataService->getByTypeId($id)){
                    return $this->responseError("对应字典数据需要先删除!");
                }
                $dictTypeService->del($id);
            }
        }
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_dict_type_index"));
    }

    public function switchStatusAction($id, Request $request, DictTypeService $dictTypeService){
        $state = (int) $request->get("state");
        $dictTypeService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

}
