<?php


namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Lib\Service\ValidateService;
use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\CoreBundle\Service\DictDataService;
use Symfony\Component\HttpFoundation\Request;

class DictDataController extends BaseAdminController
{

    public function indexAction($typeId, Request $request, Grid $grid, DictDataService $dictDataService){
        $pageSize = 40;
        $grid->setListService($dictDataService, "dictDataList",$typeId);
        $grid->text("字典标签")->field("dictLabel");
        $grid->text("字典数据值")->field("dictValue");
        $grid->boole2("字典数据状态")->field("status")->actionCall("admin_api_dict_data_switch_status", function ($obj) use($dictDataService) {
            $id = $dictDataService->getPro($obj, "id");
            $defaultValue = $dictDataService->getPro($obj, "status");
            $url = $this->generateUrl('admin_api_dict_data_switch_status', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"确认切换当前状态吗?\" {$checkStr} >";
            return $str;
        });
        $grid->text("排序")->field("fsort");
        $grid->text("备注")->field("descr");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbAddButton("admin_dict_data_add",["typeId"=>$typeId]);
        $grid->snumber("ID")->field("a.id");
        $grid->stext("字典标签")->field("a.dictLabel");
        $grid->sselect("字典数据状态？")->field("a.isAdmin")->options(function () {
            return ["全部" => -1, "开启" => 1, "关闭" => 0];
        });
        $grid->sdaterange("创建时间")->field("a.createdAt");

        //编辑等
        $grid->viewAction("admin_dict_data_view")
            ->editAction("admin_dict_data_edit")
            ->deleteAction("admin_api_dict_data_delete");

        //批量删除
        $grid->setBathDelete("admin_api_dict_data_bathdelete");

        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    public function addAction($typeId,Form $form, DictDataService $dictDataService)
    {
        $form->text("字典标签")->field("dictLabel")->isRequire(1);
        $form->textarea("字典数据值")->field("dictValue")->isRequire(1);
        $form->boole("字典数据状态")->field("status")->isRequire(1);
        $form->text("排序")->field("fsort")->isRequire(1);
        $form->textarea("备注")->field("descr")->isRequire(1);
        $formData = $form->create($this->generateUrl("admin_api_dict_data_add",["typeId"=>$typeId]));
        return $this->content()->title("添加字典数据")->breadcrumb("字典管理", "admin_dict_data_index", ["typeId"=>$typeId])->renderAdd($formData);
    }


    public function addDoAction($typeId, Request $request,
                                   DictDataService $dictDataService)
    {
        $dictLabel = $request->get("dictLabel");
        $dictValue = $request->get("dictValue");
        $status = $request->get("status");
        $fsort = $request->get("fsort");
        $descr = $request->get("descr");
        $status = $status=="on"?1:0;
        if(!$dictLabel) return $this->responseError("字典标签不能为空!");
        if(!$dictValue) return $this->responseError("字典数据值不能为空!");
        $dictDataService->add($typeId, $dictLabel, $dictValue, $fsort, $status, $descr);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_dict_data_index", ["typeId"=>$typeId]));
    }


    public function viewAction($id,View $view, DictDataService $dictDataService)
    {
        $info = $dictDataService->getById($id);
        $view->text("字典标签")->field("dictLabel")->defaultValue($info['dictLabel']);
        $view->textarea("字典数据值")->field("dictValue")->defaultValue($info['dictValue']);
        $view->boole("字典数据状态")->field("status")->defaultValue($info['status']);
        $view->text("排序")->field("fsort")->defaultValue($info['fsort']);
        $view->textarea("备注")->field("descr")->defaultValue($info['descr']);
        $formData = $view->create();
        return $this->content()->renderView($formData);
    }

    public function editAction($id, Form $form, DictDataService $dictDataService)
    {
        $info = $dictDataService->getById($id);
        $form->text("字典标签")->field("dictLabel")->isRequire(1)->defaultValue($info['dictLabel']);
        $form->textarea("字典数据值")->field("dictValue")->isRequire(1)->defaultValue($info['dictValue']);
        $form->boole("字典数据状态")->field("status")->isRequire(1)->defaultValue($info['status']);
        $form->text("排序")->field("fsort")->isRequire(1)->defaultValue($info['fsort']);
        $form->text("描述")->field("descr")->isRequire(1)->defaultValue($info['descr']);
        $formData = $form->create($this->generateUrl("admin_api_dict_data_edit",['id'=>$id]));
        return $this->content()->renderEdit($formData);
    }

    public function editDoAction(  $id,
                                   Request $request,
                                   DictDataService $dictDataService)
    {
        $info = $dictDataService->getById($id);
        $dictLabel = $request->get("dictLabel");
        $dictValue = $request->get("dictValue");
        $status = $request->get("status");
        $fsort = $request->get("fsort");
        $descr = $request->get("descr");
        $status = $status=="on"?1:0;
        if(!$dictLabel) return $this->responseError("字典标签不能为空!");
        if(!$dictValue) return $this->responseError("字典数据值不能为空!");
        $dictDataService->edit($id, $dictLabel, $dictValue, $fsort, $status, $descr);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_dict_data_index", ["typeId"=>$info["dictTypeId"]]));
    }

    public function deleteAction($id, DictDataService $dictDataService){
        $info = $dictDataService->getById($id);
        $dictDataService->del($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_dict_data_index", ["typeId"=>$info["dictTypeId"]]));
    }

    public function bathdeleteAction(Request $request, DictDataService $dictDataService){
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $dictDataService->del($id);
            }
        }
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_dict_data_index", ["typeId"=>$info["dictTypeId"]]));
    }

    public function switchStatusAction($id, Request $request, DictDataService $dictDataService){
        $state = (int) $request->get("state");
        $dictDataService->switchStatus($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

}
