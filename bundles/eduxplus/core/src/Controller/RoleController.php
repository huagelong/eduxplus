<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;
use Eduxplus\CoreBundle\Service\MenuService;
use Eduxplus\CoreBundle\Service\RoleService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RoleController extends BaseAdminController
{


    
    public function indexAction(Request $request, RoleService $roleService, Grid $grid){
        $pageSize = 40;
        $grid->setListService($roleService, "roleMenu");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->text("名称")->field("name");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");
        $grid->boole("锁定？")->field("isLock");
        $grid->textarea("描述")->field("descr");

        $grid->snumber("ID")->field("a.id");
        $grid->stext("名称")->field("a.name");
        $grid->sdatetimerange("创建时间")->field("a.createdAt");

        $grid->gbAddButton("admin_role_add");

        //绑定菜单
        $grid->setTableAction('admin_role_bindmenu', function($obj) use($roleService){
            $id = $roleService->getPro($obj, "id");
            $url = $this->generateUrl('admin_role_bindmenu',['id'=>$id]);
            return  '<a href='.$url.' data-title="绑定菜单" class=" btn btn-primary btn-xs poppage" ><i class="mdi mdi-link"></i></a>';
        });

        $grid->setTableAction('admin_role_edit', function($obj) use ($roleService){
            $isLock = $roleService->getPro($obj, "isLock");
            if($isLock) return ;
            $id = $roleService->getPro($obj, "id");
            $url = $this->generateUrl('admin_role_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-title="编辑角色" class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_role_delete', function ($obj) use ($roleService) {
            $isLock = $roleService->getPro($obj, "isLock");
            if($isLock) return ;
            $id = $roleService->getPro($obj, "id");
            $url = $this->generateUrl('admin_api_role_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?"  class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });

        //批量删除
        $grid->setBathDelete("admin_api_role_batchdelete");
        return $this->content()->renderList($grid->create($request, $pageSize));
    }

    
    public function addAction(Form $form){

        $form->text("角色名称")->field("name")->isRequire();
        $form->boole("锁定?")->field("isLock")->isRequire();
        $form->textarea("描述")->field("descr");
        $formData = $form->create($this->generateUrl("admin_api_role_add"));
        return $this->content()->breadcrumb("角色管理", "admin_role_index")->renderAdd($formData);
    }


    
    public function addDoAction(Request $request, RoleService $roleService){
        $name = $request->get("name");
        $isLock = $request->get("isLock");
        $descr = $request->get("descr");
        $isLock = $isLock=="on"?1:0;
        if(!$name) return $this->responseError("角色名称不能为空!");

        if(mb_strlen($name, 'utf-8')>20) return $this->responseError("角色名称不能超过20字!");

        if($roleService->checkName($name))  return $this->responseError("角色名称已存在!");

        $roleService->addRole($name, $isLock, $descr);

        return $this->responseMsgRedirect("添加成功!", $this->generateUrl("admin_role_index"));
    }

    
    public function editAction($id, RoleService $roleService, Form $form){
        $info = $roleService->getById($id);
        $form->text("角色名称")->field("name")->isRequire()->defaultValue($info['name']);
        $form->boole("锁定？")->field("isLock")->isRequire()->defaultValue($info['isLock']);
        $form->textarea("描述")->field("descr")->defaultValue($info['descr']);
        $formData = $form->create($this->generateUrl("admin_api_role_edit",['id'=>$id]));
        return $this->content()->renderEdit($formData);
    }

    
    public function editDoAction($id, Request $request, RoleService $roleService){
        $name = $request->get("name");
        $isLock = $request->get("isLock");
        $descr = $request->get("descr");
        $isLock = $isLock=="on"?1:0;
        if(!$name) return $this->responseError("角色名称不能为空!");

        if(mb_strlen($name, 'utf-8')>20) return $this->responseError("角色名称不能超过20字!");

        if($roleService->checkName($name, $id))  return $this->responseError("角色名称已存在!");

        $roleService->updateRole($id, $name, $isLock, $descr);

        return $this->responseMsgRedirect("编辑成功!", $this->generateUrl("admin_role_index"));
    }

    
    public function deleteAction($id, RoleService $roleService){
        $roleService->deleteRole($id);
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_role_index"));
    }

    
    public function bathDeleteAction(RoleService $roleService, Request $request){

        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $roleService->deleteRole($id);
            }
        }
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseMsgRedirect("删除成功!", $this->generateUrl("admin_role_index"));
    }

    
    public function bindMenuAction($id,  MenuService $menuService, RoleService $roleService){
        $info = $roleService->getById($id);
        $menuIds = $roleService->getRoleMenu($id);
        $data = [];
        $data['info'] = $info;
        $data['menuIds'] = $menuIds;
        $data['allMenu'] = $menuService->getAllMenu();
        return $this->render("@CoreBundle/role/bindmenu.html.twig", $data);
    }


    
    public function bindMenuDoAction($id, Request $request, RoleService $roleService){
        $idstr = $request->request->get("data");
        $ids = $idstr?explode(",", $idstr):[];
        $roleService->bindMenu($id, $ids);
        return $this->responseMsgRedirect("绑定成功!", $this->generateUrl('admin_role_index'));
    }
}
