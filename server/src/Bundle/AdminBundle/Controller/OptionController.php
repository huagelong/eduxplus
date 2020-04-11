<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 10:07
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Service\OptionService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class OptionController extends BaseAdminController
{

    /**
     * @Rest\Get("/option/index", name="admin_option_index")
     */
    public function indexAction(Request $request, Grid $grid, OptionService $optionService){
        $pageSize = 20;
        $grid->setListService($optionService, "optionList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("描述", "textarea", "descr");
        $grid->setTableColumn("健", "text", "optionKey","a.optionKey");
        $grid->setTableColumn("值", "code", "optionValue");
        $grid->setTableColumn("值类型", "text", "type", "", [1=>"文本", 2=>"文件链接"]);
        $grid->setTableColumn("是否锁定", "boole", "isLock");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        $grid->setGridBar("admin_option_add","文本配置", $this->generateUrl("admin_option_add",["type"=>1]), "fas fa-plus", "btn-success");
        $grid->setGridBar("admin_option_add","文件链接配置", $this->generateUrl("admin_option_add",["type"=>2]), "fas fa-plus", "btn-primary");

        //搜索
        $grid->setSearchField("健", "text", "a.optionKey");
        $grid->setSearchField("值类型", "boole", "type", function(){
            return ["全部"=>-1,"文本"=>1, "文件链接"=>0];
        });

        //编辑等
        $grid->setTableAction('admin_option_edit', function($obj){
            $id = $obj->getId();
            $url = $this->generateUrl('admin_option_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-title="编辑" data-width="1000px" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_option_delete', function ($obj) {
            if($obj->getIsLock()) return ;
            $id = $obj->getId();
            $url = $this->generateUrl('admin_api_option_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?"  class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/option/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/option/add/{type}", name="admin_option_add")
     */
    public function addAction($type=1, Form $form){

        $form->setFormField("健", 'text', 'optionKey' ,1);
        $valueType = $type==1?"textarea":"file";

        $options = [];
        if($type == 2){
            $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_option"]);
            $options["data-min-file-count"] = 1;
            $options['data-max-total-file-count'] = 100;
            $options["data-max-file-size"] = 1024*50;//50m
            $options["data-required"] = 1;
        }else{
            $options["data-required"] = 1;
        }

        $form->setFormAdvanceField("值", $valueType, 'optionValue' , $options);

        $form->setFormField("描述", 'textarea', 'descr' ,1);
        $form->setFormField("锁定", 'boole', 'isLock', 1);
//        $form->disableSubmit();
        $formData = $form->create($this->generateUrl("admin_api_option_add",['type'=>$type]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/option/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/option/addDo/{type}", name="admin_api_option_add")
     */
    public function addDoAction($type=1, Request $request, OptionService $optionService){
        $optionKey = $request->get("optionKey");
        $optionValue = $request->get("optionValue");
        $descr = $request->get('descr');
        $isLock = $request->get("isLock");
        $isLock = $isLock=="on"?1:0;

        if(!$optionKey) return $this->responseError("健不能为空!");
        if(!$optionValue) return $this->responseError("值不能为空!");
        if($optionService->checkOptionKey($optionKey)) return $this->responseError("健已存在!");

        $optionService->add($type, $optionKey, $optionValue, $descr, $isLock);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_option_index"));
    }

    /**
     * @Rest\Get("/option/edit/{id}", name="admin_option_edit")
     */
    public function editAction($id, Form $form, OptionService $optionService){
        $info = $optionService->getById($id);
        $type = $info['type'];
        if($info['isLock']){
            $form->setFormField("健", 'string', 'optionKey' ,1, $info['optionKey']);
        }else{
            $form->setFormField("健", 'text', 'optionKey' ,1, $info['optionKey']);
        }

        $valueType = $type==1?"textarea":"file";

        $options = [];
        if($type == 2){
            $options["data-upload-url"] = $this->generateUrl("admin_glob_upload", ["type"=>"img_option"]);
            $options["data-min-file-count"] = 1;
            $options["data-max-total-file-count"] = 100;
            $options["data-max-file-size"] = 1024*50;//50m
            $options["data-required"] = 1;
            $options['data-initial-preview'] = $info['optionValue'];
        }else{
            $options["data-required"] = 1;
        }

        $form->setFormAdvanceField("值", $valueType, 'optionValue' , $options, $info['optionValue']);

        $form->setFormField("描述", 'textarea', 'descr' ,1, $info['descr']);
        if(!$info['isLock']) {
            $form->setFormField("锁定", 'boole', 'isLock', 1, $info['isLock']);
        }
//        $form->disableSubmit();
        $formData = $form->create($this->generateUrl("admin_api_option_edit",['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/option/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/option/editDo/{id}", name="admin_api_option_edit")
     */
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

        return $this->responseSuccess("编辑成功!", $this->generateUrl("admin_option_index"));
    }

    /**
     * @Rest\Post("/api/option/deleteDO/{id}", name="admin_api_option_delete")
     */
    public function deleteDoAction($id, OptionService $optionService){
        $optionService->deleteOption($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_option_index"));
    }

}
