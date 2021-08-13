<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/8 13:21
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Lib\View\View;
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
    public function indexAction(Request $request, Grid $grid, UserService $userService)
    {
        $pageSize = 40;
        $grid->setListService($userService, "userList");
        $grid->text("#")->field("id")->sort("a.id");
        $grid->text("唯一码")->field("uuid")->sort("a.uuid");
        $grid->text("手机号码")->field("mobile");
        $grid->text("昵称")->field("displayName");
        $grid->text("姓名")->field("fullName");
        $grid->image("头像")->field("gravatar");
        $grid->text("性别")->field("sex")->options([0 => "未知", 1 => "男", 2 => "女"]);
        $grid->boole2("锁定用户")->field("isLock")->actionCall("admin_api_user_switchLock", function ($obj) {
            $id = $this->getPro($obj, "id");
            $defaultValue = $this->getPro($obj, "isLock");
            $url = $this->generateUrl('admin_api_user_switchLock', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"确认更改锁定状态吗?\" {$checkStr} >";
            return $str;
        });
        $grid->boole("管理员？")->field("isAdmin");
        $grid->text("注册来源")->field("regSource");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbButton("添加")->route("admin_user_add")
            ->url($this->generateUrl("admin_user_add"))
            ->styleClass("btn-success")->iconClass("fas fa-plus");

        //搜索
        $grid->snumber("ID")->field("a.id");
        $grid->stext("唯一码")->field("a.uuid");
        $grid->stext("手机号码")->field("a.mobile");
        $grid->stext("昵称")->field("a.displayName");
        $grid->stext("姓名")->field("a.fullName");
        $grid->sselect("管理员？")->field("a.isAdmin")->options(function () {
            return ["全部" => -1, "是" => 1, "否" => 0];
        });
        $grid->sdaterange("创建时间")->field("a.createdAt");

        //编辑等
        $grid->setTableAction('admin_user_view', function ($obj) {
            $id = $obj->getId();
            $url = $this->generateUrl('admin_user_view', ['id' => $id]);
            $str = '<a href=' . $url . ' data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="fas fa-eye"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_user_edit', function ($obj) {
            $id = $obj->getId();
            $url = $this->generateUrl('admin_user_edit', ['id' => $id]);
            $str = '<a href=' . $url . ' data-title="编辑" title="编辑" class=" btn btn-info btn-xs poppage"><i class="fas fa-edit"></i></a>';
            return  $str;
        });

        $grid->setTableAction('admin_api_user_delete', function ($obj) {
            $id = $obj->getId();
            $url = $this->generateUrl('admin_api_user_delete', ['id' => $id]);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除"  class=" btn btn-danger btn-xs ajaxDelete"><i class="fas fa-trash"></i></a>';
        });

        //批量删除
        $bathDelUrl = $this->genUrl("admin_api_user_bathdelete");
        $grid->setBathDelete("admin_api_user_bathdelete", $bathDelUrl);

        $data = [];
        $data['list'] = $grid->create($request, $pageSize);
        return $this->render("@AdminBundle/user/index.html.twig", $data);
    }


    /**
     * @Rest\Get("/user/add", name="admin_user_add")
     */
    public function addAction(Form $form, RoleService $roleService)
    {
        $form->text("手机号码")->field("mobile")->isRequire(1);
        $form->password("密码")->field("pwd1")->isRequire(1);
        $form->password("确认密码")->field("pwd2")->isRequire(1);
        $form->text("昵称")->field("displayName")->isRequire(1);
        $form->text("姓名")->field("fullName")->isRequire(1);
        $form->select("性别")->field("sex")->isRequire(1)->options(["男" => 1, "女" => 2])->placeholder("选择性别");
        $form->multiSelect("角色")->field('roles[]')->options(function () use ($roleService) {
            $all = $roleService->getAllRole();
            if (!$all) return [];
            $rs = [];
            foreach ($all as $v) {
                $rs[$v['name']] = $v['id'];
            }
            return $rs;
        })->placeholder("选择角色");


        $formData = $form->create($this->generateUrl("admin_api_user_add"));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/user/add.html.twig", $data);
    }

    /**
     * @Rest\Post("/user/add/do", name="admin_api_user_add")
     */
    public function addDoAction(
        Request $request,
        ValidateService $validateService,
        UserService $userService
    ) {
        $mobile = $request->get("mobile");
        $displayName = $request->get("displayName");
        $pwd1 = $request->get("pwd1");
        $pwd2 = $request->get("pwd2");
        $fullName = $request->get("fullName");
        $sex = $request->get("sex");
        $roles = $request->get("roles");

        if (!$mobile) return $this->responseError("手机号码不能为空!");
        if (!$validateService->mobileValidate($mobile)) return $this->responseError($this->error()->getLast());
        if ($userService->checkMobile($mobile)) return $this->responseError("手机号码已存在!");

        if (!$displayName) return $this->responseError("昵称不能为空!");
        if (!$validateService->nicknameValidate($displayName)) return $this->responseError($this->error()->getLast());
        if ($userService->checkDisplayName($displayName)) return $this->responseError("昵称已存在!");

        if (!$fullName) return $this->responseError("姓名不能为空!");
        if ($userService->checkFullName($fullName)) return $this->responseError("姓名已存在!");

        if (!$pwd1) return $this->responseError("密码不能为空!");
        if (!$validateService->passwordValid($pwd1)) return $this->responseError($this->error()->getLast());
        if ($pwd1 != $pwd2) return $this->responseError("两次密码不能为空!");

        if (!$sex) return $this->responseError("性别不能为空!");

        $userService->add($mobile, $displayName, $pwd1, $fullName, $sex, $roles);

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_user_index'));
    }

    /**
     * @Rest\Get("/user/view/{id}", name="admin_user_view")
     */
    public function viewAction($id, UserService $userService, RoleService $roleService, View $view)
    {
        $info = $userService->getById($id);

        $view->text("手机号码")->field("mobile")->defaultValue($info['mobile']);
        $view->text("昵称")->field("displayName")->defaultValue($info['displayName']);
        $view->text("姓名")->field("fullName")->defaultValue($info['fullName']);
        $view->select("性别")->field("sex")->options(["男" => 1, "女" => 2])->defaultValue($info['sex']);
        $view->boole("是否管理员")->field("isAdmin")->defaultValue($info['isAdmin']);
        $view->boole("是否锁定")->field("isLock")->defaultValue($info['isLock']);

        $myRoleUsers = $userService->getMyRoleIds($id);
        $view->multiSelect("角色")->defaultValue($myRoleUsers)->field('roles[]')->options(function () use ($roleService) {
            $all = $roleService->getAllRole();
            if (!$all) return [];
            $rs = [];
            foreach ($all as $v) {
                $rs[$v['name']] = $v['id'];
            }
            return $rs;
        });

        $viewData = $view->create();
        $data = [];
        $data["viewData"] = $viewData;
        return $this->render("@AdminBundle/user/view.html.twig", $data);
    }

    /**
     * @Rest\Get("/user/edit/{id}", name="admin_user_edit")
     */
    public function editAction($id, UserService $userService, RoleService $roleService, Form $form)
    {
        $info = $userService->getById($id);

        $form->text("手机号码")->field("mobile")->isRequire(1)->defaultValue($info['mobile']);
        $form->text("昵称")->field("displayName")->isRequire(1)->defaultValue($info['displayName']);
        $form->text("姓名")->field("fullName")->isRequire(1)->defaultValue($info['fullName']);
        $form->select("性别")->field("sex")->isRequire(1)->options(["男" => 1, "女" => 2])->placeholder("选择性别")->defaultValue($info['sex']);

        $myRoleUsers = $userService->getMyRoleIds($id);
        $form->multiSelect("角色")->defaultValue($myRoleUsers)->field('roles[]')->options(function () use ($roleService) {
            $all = $roleService->getAllRole();
            if (!$all) return [];
            $rs = [];
            foreach ($all as $v) {
                $rs[$v['name']] = $v['id'];
            }
            return $rs;
        })->placeholder("选择角色");

        $formData = $form->create($this->generateUrl("admin_api_user_edit", ["id" => $id]));
        $data = [];
        $data["formData"] = $formData;
        return $this->render("@AdminBundle/user/edit.html.twig", $data);
    }

    /**
     * @Rest\Post("/user/edit/do/{id}", name="admin_api_user_edit")
     */
    public function editDoAction(
        $id,
        Request $request,
        ValidateService $validateService,
        UserService $userService
    ) {
        $mobile = $request->get("mobile");
        $displayName = $request->get("displayName");
        $fullName = $request->get("fullName");
        $sex = $request->get("sex");
        $roles = $request->get("roles");

        if (!$mobile) return $this->responseError("手机号码不能为空!");
        if (!$validateService->mobileValidate($mobile)) return $this->responseError($this->error()->getLast());
        if ($userService->checkMobile($mobile, $id)) return $this->responseError("手机号码已存在!");

        if (!$displayName) return $this->responseError("昵称不能为空!");
        if (!$validateService->nicknameValidate($displayName)) return $this->responseError($this->error()->getLast());
        if ($userService->checkDisplayName($displayName, $id)) return $this->responseError("昵称已存在!");

        if (!$fullName) return $this->responseError("姓名不能为空!");
        if ($userService->checkFullName($fullName, $id)) return $this->responseError("姓名已存在!");

        if (!$sex) return $this->responseError("性别不能为空!");

        $userService->edit($id, $mobile, $displayName, $fullName, $sex, $roles);

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_user_index'));
    }

    /**
     * @Rest\Post("/user/delete/do/{id}", name="admin_api_user_delete")
     */
    public function deleteAction($id, UserService $userService)
    {
        $userService->delUser($id);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_user_index'));
    }

    /**
     * @Rest\Post("/user/bathdelete/do", name="admin_api_user_bathdelete")
     */
    public function bathdeleteAction(Request $request, UserService $userService)
    {
        $ids = $request->get("ids");
        if($ids){
            foreach ($ids as $id){
                $userService->delUser($id);
            }
        }

        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_user_index'));
    }

    /**
     * @Rest\Post("/user/switchLock/do/{id}", name="admin_api_user_switchLock")
     */
    public function switchLockAction($id, UserService $userService, Request $request)
    {
        $state = (int) $request->get("state");
        $userService->switchLock($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }
}
