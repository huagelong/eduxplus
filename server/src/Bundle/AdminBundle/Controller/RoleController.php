<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Lib\Grid\Grid;
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
        $grid->setGridBar("添加", $this->generateUrl("admin_role_add"), "fas fa-plus", "btn-success");
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
    public function addAction(){

    }

    /**
     * @Rest\Get("/role/edit/{id}", name="admin_role_edit")
     */
    public function editAction(){

    }

    /**
     * @Rest\Post("/api/role/delete/{id}", name="admin_api_role_delete")
     */
    public function deleteAction(){

    }

}
