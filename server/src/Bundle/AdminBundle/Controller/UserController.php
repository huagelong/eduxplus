<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/8 13:21
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Service\UserService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Bundle\AdminBundle\Lib\Form\Form;
use App\Bundle\AdminBundle\Lib\Grid\Grid;

class UserController extends BaseAdminController
{

    /**
     * @Rest\Get("/user/index", name="admin_user_index")
     */
    public function indexAction(Request $request, Grid $grid, UserService $userService){
        $pageSize = 20;
        $grid->setListService($userService, "userList");
        $grid->setTableColumn("#", "text", "id","a.id");
        $grid->setTableColumn("唯一码", "text", "uuid","a.uuid");
        $grid->setTableColumn("手机号码", "text", "mobile");
        $grid->setTableColumn("昵称", "text", "displayName");
        $grid->setTableColumn("姓名", "text", "fullName");
        $grid->setTableColumn("头像", "image", "gravatar");
        $grid->setTableColumn("性别", "text", "sex", "", [0=>"未知",1=>"男", 2=>"女"]);
        $grid->setTableColumn("是否被锁定", "boole", "isLock");
        $grid->setTableColumn("是否管理员", "boole", "isAdmin");
        $grid->setTableColumn("注册来源", "text", "regSource");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        $grid->setGridBar("admin_user_add","添加", $this->generateUrl("admin_role_add"), "fas fa-plus", "btn-success");

        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("唯一码", "text", "a.uuid");
        $grid->setSearchField("手机号码", "text", "a.mobile");
        $grid->setSearchField("昵称", "text", "a.displayName");
        $grid->setSearchField("姓名", "text", "a.fullName");
        $grid->setSearchField("是否管理员", "boole", "_role");

        $grid->setTableAction('admin_user_edit', function($obj){
            $id = $obj['id'];
            $url = $this->generateUrl('admin_user_edit',['id'=>$id]);
            $str = '<a href='.$url.' data-title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_user_delete', function ($obj) {
            $id = $obj['id'];
            $url = $this->generateUrl('admin_api_user_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?"  class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/user/index.html.twig", $data);
    }


    /**
     * @Rest\Get("/user/add", name="admin_user_add")
     */
    public function addAction(Form $form){

    }

    /**
     * @Rest\Get("/api/user/adddo", name="admin_api_user_add")
     */
    public function addDoAction(){

    }

    /**
     * @Rest\Get("/user/edit", name="admin_user_edit")
     */
    public function editAction(){

    }

    /**
     * @Rest\Get("/api/user/editdo", name="admin_api_user_edit")
     */
    public function editDoAction(){

    }

    /**
     * @Rest\Get("/api/user/deletedo", name="admin_api_user_delete")
     */
    public function deleteAction(){

    }

}
