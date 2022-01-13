<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 10:07
 */

namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Service\OptionService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class OptionController extends BaseAdminController
{

    
    public function indexAction(Request $request, Grid $grid, OptionService $optionService){
        $pageSize = 40;
        $grid->setListService($optionService, "optionList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->textarea("配置说明")->field("descr");
//        $grid->text("健")->field("optionKey")->sort("a.optionKey");
        $grid->code("值")->field("optionValue");
        $grid->text("组")->field("optionGroup")->sort("a.optionGroup");
        $grid->text("值类型")->field("type")->options([1=>"文本", 2=>"文件链接"]);
//        $grid->boole("锁定？")->field("isLock");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbAddButton("admin_option_add", ["type"=>1], "文本配置");
        $grid->gbAddButton("admin_option_add", ["type"=>2], "上传文件配置");

        //搜索
        $grid->sselect("配置分组")->field("a.optionGroup")->options($optionService->getAllOptionGroup());
        $grid->stext("配置说明")->field("a.descr");
        $grid->sselect("值类型")->field("a.type")->options(["全部"=>"","文本"=>1, "文件链接"=>2]);
          
        //编辑等
        $grid->editAction("admin_option_edit");

        $grid->setTableAction('admin_api_option_delete', function ($obj) {
            if($obj->getIsLock()) return ;
            $id = $obj->getId();
            $url = $this->generateUrl('admin_api_option_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除"  class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });

        //批量删除
        $grid->setBathDelete("admin_api_option_bathdelete");
        
        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    
    public function addAction($type, Form $form, OptionService $optionService){
        $form->textarea("配置说明")->field("descr")->isRequire(1);

        $form->text("健")->field("optionKey")->isRequire(1)->placeholder("推荐用英文字母,英文逗点.");

        $options = [];
        if($type == 2){
            $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_option"]);
            $options["data-min-file-count"] = 1;
            $options['data-max-total-file-count'] = 100;
            $options["data-max-file-size"] = 1024*5;//5m
            $options["data-required"] = 1;
        }else{
            $options["data-required"] = 1;
        }

        if($type == 1){
            $form->textarea("值")->field('optionValue')->attr($options);
        }else{
            $form->file("值")->field('optionValue')->attr($options);
        }

        $form->select("配置分组")->field("optionGroup")->options($optionService->getAllOptionGroup());
        $form->boole("锁定")->field("isLock")->isRequire(1);
        $formData = $form->create($this->generateUrl("admin_api_option_add",['type'=>$type]));
        return $this->content()->title($type==2?"添加上传文件配置":"添加文本配置")->breadcrumb("系统配置管理", "admin_option_index")->renderAdd($formData);
    }

    
    public function addDoAction($type, Request $request, OptionService $optionService){
        $optionKey = $request->get("optionKey");
        $optionValue = $request->get("optionValue");
        $descr = $request->get('descr');
        $isLock = $request->get("isLock");
        $optionGroup = $request->get("optionGroup");
        $isLock = $isLock=="on"?1:0;

        if(!$optionKey) return $this->responseError("健不能为空!");
        if(!$optionValue) return $this->responseError("值不能为空!");
        if($optionService->checkOptionKey($optionKey)) return $this->responseError("健已存在!");

        $optionService->add($type, $optionKey, $optionValue, $descr, $isLock, $optionGroup);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_option_index"));
    }

    
    public function editAction($id, Form $form, OptionService $optionService){
        $info = $optionService->getById($id);
        $type = $info['type'];

        $form->textarea("配置说明")->field("descr")->isRequire(1)->defaultValue($info['descr']);

        if($info['isLock']){
            $form->string("健")->field("optionKey")->isRequire(1)->defaultValue($info['optionKey'])->placeholder("推荐用英文字母,英文逗点.");
        }else{
            $form->text("健")->field("optionKey")->isRequire(1)->defaultValue($info['optionKey'])->placeholder("推荐用英文字母,英文逗点.");
        }

        $options = [];
        if($type == 2){
            $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_option"]);
            $options["data-min-file-count"] = 1;
            $options["data-max-total-file-count"] = 100;
            $options["data-max-file-size"] = 1024*5;//5m
            $options["data-required"] = 1;
            $options['data-initial-preview'] = $info['optionValue'];
            $options['data-initial-preview-config']= $optionService->getInitialPreviewConfig($info['optionValue']);
        }else{
            $options["data-required"] = 1;
        }

        if($type == 1){
            $form->textarea("值")->field('optionValue')->attr($options)->defaultValue($info['optionValue']);
        }else{
            $form->file("值")->field('optionValue')->attr($options)->defaultValue($info['optionValue']);
        }


        if(!$info['isLock']) {
            $form->boole("锁定")->field("isLock")->isRequire(1)->defaultValue($info['isLock']);
        }
        $formData = $form->create($this->generateUrl("admin_api_option_edit",['id'=>$id]));
        return $this->content()->renderEdit($formData);
    }

    
    public function editDoAction($id, Request $request, OptionService $optionService){
        $info = $optionService->getById($id);
        $optionKey = $request->get("optionKey");
        $optionValue = $request->get("optionValue");
        $descr = $request->get('descr');
        $isLock = $request->get("isLock");
        $isLock = $isLock=="on"?1:0;

        if(!$optionKey && !$info['isLock']) return $this->responseError("健不能为空!");
        if(!$optionValue) return $this->responseError("值不能为空!");
        if($optionService->checkOptionKey($optionKey, $id) && !$info['isLock']) return $this->responseError("健已存在!");

        $optionService->edit($id, $optionKey, $optionValue, $descr, $isLock);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_option_index"));
    }

    
    public function deleteDoAction($id, OptionService $optionService){
        $optionService->deleteOption($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_option_index"));
    }

    
    public function bathdeleteDoAction(Request $request, OptionService $optionService){
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $optionService->deleteOption($id);
            }
        }
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_option_index"));
    }

}
