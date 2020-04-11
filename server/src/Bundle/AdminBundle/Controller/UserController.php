<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/8 13:21
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Service\RoleService;
use App\Bundle\AdminBundle\Service\UserService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use App\Bundle\AppBundle\Lib\Service\ValidateService;
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
//        $grid->setTableColumn("是否被锁定", "boole", "isLock");
        $grid->setTableActionColumn("admin_api_user_switchLock", "锁定用户", "boole2", "isLock", null,null,function($obj){
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "isLock");
            $url = $this->generateUrl('admin_api_user_switchLock', ['id' => $id]);
            $checkStr = $defaultValue?"checked":"";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"确认更改锁定状态吗?\" {$checkStr} >";
            return $str;
        });
        $grid->setTableColumn("是否管理员", "boole", "isAdmin");
        $grid->setTableColumn("注册来源", "text", "regSource");
        $grid->setTableColumn("创建时间", "datetime", "createdAt", "a.createdAt");

        $grid->setGridBar("admin_user_add","添加", $this->generateUrl("admin_user_add"), "fas fa-plus", "btn-success");

        //搜索
        $grid->setSearchField("ID", "number", "a.id");
        $grid->setSearchField("唯一码", "text", "a.uuid");
        $grid->setSearchField("手机号码", "text", "a.mobile");
        $grid->setSearchField("昵称", "text", "a.displayName");
        $grid->setSearchField("姓名", "text", "a.fullName");
        $grid->setSearchField("是否管理员", "boole", "_isAdmin", function(){
            return ["全部"=>-1,"是"=>1, "否"=>0];
        });
        $grid->setSearchField("创建时间", "daterange", "a.createdAt");

        //编辑等
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
    public function addAction(Form $form, RoleService $roleService){
        $form->setFormField("手机号码", 'text', 'mobile' ,1);
        $form->setFormField("密码", 'password', 'pwd1' ,1);
        $form->setFormField("确认密码", 'password', 'pwd2' ,1);
        $form->setFormField("昵称", 'text', 'displayName' ,1);
        $form->setFormField("姓名", 'text', 'fullName' ,1);
        $form->setFormField("性别", 'select', 'sex' ,1, "", function(){
            return ["男"=>1, "女"=>2];
        }, "选择性别");
        $form->setFormField("角色", 'multiSelect', 'roles[]', 0, "", function() use($roleService){
                $all = $roleService->getAllRole();
                if(!$all) return [];
                $rs = [];
                foreach ($all as $v){
                    $rs[$v['name']] = $v['id'];
                }
                return $rs;
        }, "选择角色");
        $formData = $form->create($this->generateUrl("admin_api_user_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/user/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/user/adddo", name="admin_api_user_add")
     */
    public function addDoAction(Request $request, ValidateService $validateService,
                                UserService $userService){
        $mobile = $request->get("mobile");
        $displayName = $request->get("displayName");
        $pwd1 = $request->get("pwd1");
        $pwd2 = $request->get("pwd2");
        $fullName = $request->get("fullName");
        $sex = $request->get("sex");
        $roles = $request->get("roles");

        if(!$mobile) return $this->responseError("手机号码不能为空!");
        if(!$validateService->mobileValidate($mobile)) return $this->responseError($this->error()->getLast());
        if($userService->checkMobile($mobile)) return $this->responseError("手机号码已存在!");

        if(!$displayName) return $this->responseError("昵称不能为空!");
        if(!$validateService->nicknameValidate($displayName)) return $this->responseError($this->error()->getLast());
        if($userService->checkDisplayName($displayName)) return $this->responseError("昵称已存在!");

        if(!$fullName) return $this->responseError("姓名不能为空!");
        if($userService->checkFullName($fullName)) return $this->responseError("姓名已存在!");

        if(!$pwd1) return $this->responseError("密码不能为空!");
        if(!$validateService->passwordValid($pwd1)) return $this->responseError($this->error()->getLast());
        if($pwd1 != $pwd2) return $this->responseError("两次密码不能为空!");

        if(!$sex) return $this->responseError("性别不能为空!");

        $userService->add($mobile, $displayName, $pwd1, $fullName, $sex, $roles);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_user_index'));
    }

    /**
     * @Rest\Get("/user/edit/{id}", name="admin_user_edit")
     */
    public function editAction($id, UserService $userService, RoleService $roleService, Form $form){
        $info = $userService->getById($id);
        $form->setFormField("手机号码", 'text', 'mobile' ,1, $info['mobile']);
        $form->setFormField("昵称", 'text', 'displayName' ,1, $info['displayName']);
        $form->setFormField("姓名", 'text', 'fullName' ,1, $info['fullName']);
        $form->setFormField("性别", 'select', 'sex' ,1, $info['sex'], function(){
            return ["男"=>1, "女"=>2];
        }, "选择性别");

        $myRoleUsers = $userService->getMyRoleIds($id);
        $form->setFormField("角色", 'multiSelect', 'roles[]', 0, $myRoleUsers, function() use($roleService){
            $all = $roleService->getAllRole();
            if(!$all) return [];
            $rs = [];
            foreach ($all as $v){
                $rs[$v['name']] = $v['id'];
            }
            return $rs;
        }, "选择角色");

        $formData = $form->create($this->generateUrl("admin_api_user_edit", ["id"=>$id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/user/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/api/user/editdo/{id}", name="admin_api_user_edit")
     */
    public function editDoAction($id, Request $request, ValidateService $validateService,
                                 UserService $userService){
        $mobile = $request->get("mobile");
        $displayName = $request->get("displayName");
        $fullName = $request->get("fullName");
        $sex = $request->get("sex");
        $roles = $request->get("roles");

        if(!$mobile) return $this->responseError("手机号码不能为空!");
        if(!$validateService->mobileValidate($mobile)) return $this->responseError($this->error()->getLast());
        if($userService->checkMobile($mobile, $id)) return $this->responseError("手机号码已存在!");

        if(!$displayName) return $this->responseError("昵称不能为空!");
        if(!$validateService->nicknameValidate($displayName)) return $this->responseError($this->error()->getLast());
        if($userService->checkDisplayName($displayName, $id)) return $this->responseError("昵称已存在!");

        if(!$fullName) return $this->responseError("姓名不能为空!");
        if($userService->checkFullName($fullName, $id)) return $this->responseError("姓名已存在!");

        if(!$sex) return $this->responseError("性别不能为空!");

        $userService->edit($id, $mobile, $displayName, $fullName, $sex, $roles);

        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_user_index'));
    }

    /**
     * @Rest\Post("/api/user/deletedo/{id}", name="admin_api_user_delete")
     */
    public function deleteAction($id, UserService $userService){
        $userService->delUser($id);
        return $this->responseSuccess("操作成功!", $this->generateUrl('admin_user_index'));
    }

    /**
     * @Rest\Post("/api/user/switchLockDo/{id}", name="admin_api_user_switchLock")
     */
    public function switchLockAction($id, UserService $userService, Request $request){
        $state = (int) $request->get("state");
        $userService->switchLock($id, $state);
        return $this->responseSuccess("操作成功!");
    }

}
