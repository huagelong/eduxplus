<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;
use App\Bundle\AdminBundle\Service\MenuService;
use App\Bundle\AdminBundle\Service\RoleService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class RoleController extends BaseAdminController
{


    /**
     * @Rest\Get("/role/index", name="admin_role_index")
     */
    public function indexAction(Request $request, RoleService $roleService, Grid $grid){
        $pageSize = 20;
        $grid->setService($roleService, "roleMenu");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("名称", "text", "name");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");
        $grid->setTableColumn("是否锁定", "boole", "isLock");
        $grid->setTableColumn("描述", "textarea", "descr");
        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("名称", "text", "a.name");
        $grid->setSearchField("创建时间", "datetimerange", "a.createdAt");
        $grid->setGridBar("添加", $this->generateUrl("admin_role_add"), "fas fa-plus", "btn-success");
        //绑定菜单
        $grid->setTableAction(function($obj){
            $id = $obj->getId();
            $url = $this->generateUrl('admin_role_bindmenu',['id'=>$id]);
            return  '<a href='.$url.' data-title="绑定菜单" class=" btn btn-primary btn-xs poppage" ><i class="fa fa-chain"></i></a>';
        });
        $grid->setTableAction(function($obj){
            if($obj->getIsLock()) return ;
            $id = $obj->getId();
            $url = $this->generateUrl('admin_role_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-title="编辑角色" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });
        $grid->setTableAction(function($obj){
            if($obj->getIsLock()) return ;
            $id = $obj->getId();
            $url = $this->generateUrl('admin_api_role_delete',['id'=>$id]);
            return  '<a href='.$url.' data-confirm="确认要删除吗?"  class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/role/index.html.twig", $data);
    }

    /**
     * @Rest\Get("/role/add", name="admin_role_add")
     */
    public function addAction(Form $form){

        $form->setFormField("角色名称", 'text', 'name' ,1);
        $form->setFormField("是否锁定", 'boole', 'isLock', 1);
        $form->setFormField("描述", 'textarea', 'descr', 0);
        $formData = $form->create($this->generateUrl("admin_api_role_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/role/add.html.twig", $data);
    }


    /**
     * @Rest\Post("/api/role/addDo", name="admin_api_role_add")
     */
    public function addDoAction(Request $request, RoleService $roleService){
        $name = $request->get("name");
        $isLock = $request->get("isLock");
        $descr = $request->get("descr");
        $isLock = $isLock=="on"?1:0;
        if(!$name) return $this->responseError("角色名称不能为空!");

        if(mb_strlen($name, 'utf-8')>20) return $this->responseError("角色名称不能超过20字!");

        if($roleService->checkName($name))  return $this->responseError("角色名称已存在!");

        $roleService->addRole($name, $isLock, $descr);

        return $this->responseSuccess("添加成功!", $this->generateUrl("admin_role_index"));
    }

    /**
     * @Rest\Get("/role/edit/{id}", name="admin_role_edit")
     */
    public function editAction($id, RoleService $roleService, Form $form){
        $info = $roleService->getById($id);
        $form->setFormField("角色名称", 'text', 'name' ,1, $info['name']);
        $form->setFormField("是否锁定", 'boole', 'isLock', 1, $info['isLock']);
        $form->setFormField("描述", 'textarea', 'descr', 0, $info['descr']);
        $formData = $form->create($this->generateUrl("admin_api_role_edit",['id'=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/role/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/role/editDo/{id}", name="admin_api_role_edit")
     */
    public function editDoAction($id, Request $request, RoleService $roleService){
        $name = $request->get("name");
        $isLock = $request->get("isLock");
        $descr = $request->get("descr");
        $isLock = $isLock=="on"?1:0;
        if(!$name) return $this->responseError("角色名称不能为空!");

        if(mb_strlen($name, 'utf-8')>20) return $this->responseError("角色名称不能超过20字!");

        if($roleService->checkName($name, $id))  return $this->responseError("角色名称已存在!");

        $roleService->updateRole($id, $name, $isLock, $descr);

        return $this->responseSuccess("编辑成功!", $this->generateUrl("admin_role_index"));
    }

    /**
     * @Rest\Post("/api/role/deleteDo/{id}", name="admin_api_role_delete")
     */
    public function deleteAction($id, RoleService $roleService){
        $roleService->deleteRole($id);
        return $this->responseSuccess("删除成功!", $this->generateUrl("admin_role_index"));
    }

    /**
     * @Rest\Get("/role/bindmenu/{id}", name="admin_role_bindmenu")
     */
    public function bindMenuAction($id,  MenuService $menuService, RoleService $roleService){
        $info = $roleService->getById($id);
        $data = [];
        $data['info'] = $info;
        $data['allMenu'] = $menuService->getAllMenu();
        return $this->render("@AdminBundle/role/bindmenu.html.twig", $data);
    }


    /**
     * @Rest\Post("/api/role/bindmenudo/{id}", name="admin_api_role_bindmenu")
     */
    public function bindMenuDoAction($id, Request $request, RoleService $roleService){
        $idstr = $request->request->get("data");
        $ids = $idstr?explode(",", $idstr):[];
        $roleService->bindMenu($id, $ids);
        return $this->responseSuccess("绑定成功!", $this->generateUrl('admin_role_index'));
    }
}
