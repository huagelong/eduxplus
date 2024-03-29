<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/8 13:21
 */

namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\View\View;
use Eduxplus\CoreBundle\Service\RoleService;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\CoreBundle\Lib\Service\ValidateService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Lib\Form\Form;
use Eduxplus\CoreBundle\Lib\Grid\Grid;

class UserController extends BaseAdminController
{

    
    public function indexAction(Request $request, Grid $grid, UserService $userService)
    {
        $pageSize = 40;
        $grid->setListService($userService, "userList");
        $grid->text("ID")->field("id")->sort("a.id");
        $grid->badgeSuccess("学号")->field("sno")->sort("a.sno");
        $grid->text("手机号码")->field("mobile");
        $grid->badgeInfo("昵称")->field("displayName");
        $grid->badgePrimary("姓名")->field("fullName");
        $grid->image("头像")->field("gravatar");
        $grid->text("性别")->field("sex")->options(function () {
            return [0 => "未知", 1 => "男", 2 => "女"];
        });
        $grid->boole2("锁定用户")->field("isLock")->actionCall("admin_api_user_switchLock", function ($obj) use($userService) {
            $id = $userService->getPro($obj, "id");
            $defaultValue = $userService->getPro($obj, "isLock");
            $url = $this->generateUrl('admin_api_user_switchLock', ['id' => $id]);
            $checkStr = $defaultValue ? "checked" : "";
            $str = "<input type=\"checkbox\" data-bootstrap-switch-ajaxput href=\"{$url}\" data-confirm=\"确认更改锁定状态吗?\" {$checkStr} >";
            return $str;
        });
        $grid->boole("可以登陆后台？")->field("isAdmin");
        $grid->text("注册来源")->field("regSource");
        $grid->datetime("创建时间")->field("createdAt")->sort("a.createdAt");

        $grid->gbAddButton("admin_user_add");
        //搜索
        $grid->snumber("ID")->field("a.id");
        $grid->stext("学号")->field("a.sno");
        $grid->stext("手机号码")->field("a.mobile");
        $grid->stext("昵称")->field("a.displayName");
        $grid->stext("姓名")->field("a.fullName");
        $grid->sselect("登陆后台？")->field("a.isAdmin")->options(function () {
            return ["全部" => -1, "是" => 1, "否" => 0];
        });
        $grid->sdaterange("创建时间")->field("a.createdAt");

        //编辑等
        $grid->setTableAction("admin_user_resetPwd", function ($obj) use($userService){
            if(is_array($obj)){
                $id = $obj["id"];
            }else{
                $id = $obj->getId();
            }
            $params = ['id' => $id];
            $url = $userService->genUrl("admin_user_resetPwd", $params);
            return '<a href=' . $url . ' data-confirm="确认要重置吗?" title="重置密码"  class=" btn btn-warning btn-xs ajaxPost"><i class="mdi mdi-lock-reset"></i></a>';
        });
        $grid->viewAction("admin_user_view")
            ->editAction("admin_user_edit")
            ->deleteAction("admin_api_user_delete");

        //批量删除
        $grid->setBathDelete("admin_api_user_bathdelete");

        return $this->content()->renderList($grid->create($request, $pageSize));
    }


    
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
        })->placeholder("选择角色，设置有角色则可以登陆后台.");


        $formData = $form->create($this->generateUrl("admin_api_user_add"));

        return $this->content()->title("添加用户")
            ->breadcrumb("用户管理", "admin_user_index")
            ->renderAdd($formData);
    }

    
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

    
    public function viewAction($id, UserService $userService, RoleService $roleService, View $view)
    {
        $info = $userService->getById($id);

        $view->text("手机号码")->field("mobile")->defaultValue($info['mobile']);
        $view->text("昵称")->field("displayName")->defaultValue($info['displayName']);
        $view->text("姓名")->field("fullName")->defaultValue($info['fullName']);
        $view->text("学号")->field("sno")->defaultValue($info['sno']);
        $view->select("性别")->field("sex")->options(["男" => 1, "女" => 2])->defaultValue($info['sex']);
        $view->boole("是否可以登陆后台")->field("isAdmin")->defaultValue($info['isAdmin']);
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

        return $this->content()->renderView($viewData);
    }

    
    public function editAction($id, UserService $userService, RoleService $roleService, Form $form)
    {
        $info = $userService->getById($id);
        
        $form->string("手机号码")->field("mobile")->isRequire(1)->defaultValue($info['mobile']);
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
        return $this->content()->renderEdit($formData);
    }

    
    public function editDoAction(
        $id,
        Request $request,
        ValidateService $validateService,
        UserService $userService
    ) {
        $displayName = $request->get("displayName");
        $fullName = $request->get("fullName");
        $sex = $request->get("sex");
        $roles = $request->get("roles");

        if (!$displayName) return $this->responseError("昵称不能为空!");
        if (!$validateService->nicknameValidate($displayName)) return $this->responseError($this->error()->getLast());
        if ($userService->checkDisplayName($displayName, $id)) return $this->responseError("昵称已存在!");

        if (!$fullName) return $this->responseError("姓名不能为空!");
        if ($userService->checkFullName($fullName, $id)) return $this->responseError("姓名已存在!");

        if (!$sex) return $this->responseError("性别不能为空!");

        $userService->edit($id, $displayName, $fullName, $sex, $roles);

        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_user_index'));
    }

    
    public function deleteAction($id, UserService $userService)
    {
        $userService->delUser($id);
        return $this->responseMsgRedirect("操作成功!", $this->generateUrl('admin_user_index'));
    }

    
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

    
    public function switchLockAction($id, UserService $userService, Request $request)
    {
        $state = (int) $request->get("state");
        $userService->switchLock($id, $state);
        return $this->responseMsgRedirect("操作成功!");
    }

    
    public function changePwdAction(Form $form){
        $form->password("旧密码")->field("oldpwd")->isRequire(1);
        $form->password("新密码")->field("pwd")->isRequire(1);
        $form->password("重复新密码")->field("repwd")->isRequire(1);
        $formData = $form->create($this->generateUrl("admin_user_changePwdDo"));
        return $this->content()->renderEdit($formData);
    }

    public function changePwdDoAction(UserService $userService, ValidateService $validateService, Request $request){
        $oldpwd = $request->get("oldpwd");
        $pwd = $request->get("pwd");
        $repwd = $request->get("repwd");
        if(!$oldpwd) return $this->responseError("旧密码不能为空");
        if(!$pwd) return $this->responseError("新密码不能为空");
        if($pwd != $repwd) return $this->responseError("两次密码不相等!");

        
        $uid = $this->getUid();
        if(!$userService->checkPwd($uid, $oldpwd)){
            return $this->responseError("旧密码错误!");
        }

        if(!$validateService->passwordValid($pwd)){
            return $this->responseError($this->error()->getLast());
        }

        $userService->changePwd($uid, $pwd);
        return $this->responseMsgRedirect("操作成功！请重新用新密码登录", $this->genUrl("admin_logout"));
    }

    public function resetPwdAction($id, UserService $userService){
        $userService->resetPwd($id);
        return $this->responseSuccess([],"操作成功!");
    }
}
