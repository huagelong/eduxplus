<?php

namespace Eduxplus\CoreBundle\DataFixtures\Fixtures;

use Eduxplus\CoreBundle\Entity\BaseMenu;
use Eduxplus\CoreBundle\Entity\BaseRoleMenu;
use Eduxplus\CoreBundle\Lib\Base\BaseService;

class MenuFixtures
{
    
    protected $baseService;

    public function __construct(BaseService $baseService)
    {
        $this->baseService = $baseService;
    }

    public function load($roleId)
    {

        //新增菜单并绑定角色
        $sysMenuId = $this->addMenu("首页", "首页", 0, "admin_index", "mdi  mdi-home", 0, $roleId, 1, 0, 1, 1);
        $this->addMenu("控制面板", "控制面板", $sysMenuId, "admin_dashboard", "mdi  mdi-home", 0, $roleId, 1, 0, 1, 1);
        $this->addMenu("关于", "关于", $sysMenuId, "admin_about", "mdi  mdi-information-variant", 1, $roleId, 1, 0, 0, 1);
        $this->addMenu("文件上传", "文件上传处理", 0, "admin_glob_upload", "mdi  mdi-upload", 1, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索用户名", "搜索用户名", 0, "admin_api_glob_searchUserDo", "", 2, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索管理员", "搜索管理员", 0, "admin_api_glob_searchAdminUserDo", "", 3, $roleId, 1, 1, 0, 1);
        $this->addMenu("修改密码界面", "修改密码界面", 0, "admin_user_changePwd", "", 4, $roleId, 1, 1, 0, 1);
        $this->addMenu("修改密码处理", "修改密码处理", 0, "admin_user_changePwdDo", "", 5, $roleId, 1, 1, 0, 1);
        $this->addMenu("登录", "登录", 0, "admin_login", "", 6, $roleId, 1, 1, 0, 1);
        $this->addMenu("退出", "退出", 0, "admin_logout", "", 7, $roleId, 1, 1, 0, 1);

        $this->addMenu("获取阿里云点播视频上传地址和凭证", "获取阿里云点播视频上传地址和凭证", 0, "admin_api_glob_aliyunVodCreateUploadVideoDo", "", 6, $roleId, 1, 1, 0, 1);
        $this->addMenu("阿里云刷新视频上传凭证", "阿里云刷新视频上传凭证", 0, "admin_api_glob_aliyunVodRefreshUploadVideoDo", "", 7, $roleId, 1, 1, 0, 1);
        $this->addMenu("阿里云点播播放信息", "阿里云点播播放信息", 0, "admin_api_glob_getAliyunVodPlayInfoDo", "", 8, $roleId, 1, 1, 0, 1);

        $this->addMenu("获取腾讯云点播视频上传凭证", "获取腾讯云点播视频上传凭证", 0, "admin_api_glob_tengxunyunSignatureDo", "", 9, $roleId, 1, 1, 0, 1);
        $this->addMenu("获取腾讯云点播播放网址加密", "获取腾讯云点播播放网址加密", 0, "admin_api_glob_tengxunyunVodEncryptionPlayUrlDo", "", 10, $roleId, 1, 1, 0, 1);
        $this->addMenu("获取腾讯云点播超级播放器签名", "获取腾讯云点播超级播放器签名", 0, "admin_api_glob_tengxunyunVodAndvancePlaySignDo", "", 11, $roleId, 1, 1, 0, 1);

        //系统模块
        $sysMenuId = $this->addMenu("系统", "系统方面的管理", 0, "", "mdi  mdi-cogs", 3, $roleId, 1, 0, 1);
        //菜单
        $menuMgId = $this->addMenu("菜单管理", "管理菜单以及对应页面的权限", $sysMenuId, "admin_menu_index", "", 3, $roleId, 1, 0, 1);
        $this->addMenu("添加菜单页面", "菜单新增页面", $menuMgId, "admin_menu_add", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加菜单", "菜单新增处理", $menuMgId, "admin_api_menu_add", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("查看菜单页面", "菜单展示页面", $menuMgId, "admin_menu_view", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("编辑菜单页面", "菜单编辑展示页面", $menuMgId, "admin_menu_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑菜单", "菜单编辑处理", $menuMgId, "admin_api_menu_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除菜单", "删除菜单", $menuMgId, "admin_api_menu_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("更新菜单排序", "更新菜单排序", $menuMgId, "admin_api_menu_updateSort", "", 6, $roleId, 1, 1, 0);
        //角色
        $roleMgId = $this->addMenu("角色管理", "管理角色", $sysMenuId, "admin_role_index", "", 1, $roleId, 1, 0, 1);
        $this->addMenu("添加角色页面", "显示添加角色页面", $roleMgId, "admin_role_add", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加角色", "添加角色处理", $roleMgId, "admin_api_role_add", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("编辑角色页面", "显示编辑角色页面", $roleMgId, "admin_role_edit", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("编辑角色", "编辑角色处理", $roleMgId, "admin_api_role_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("删除角色", "删除角色处理", $roleMgId, "admin_api_role_delete", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("批量删除角色", "批量删除角色处理", $roleMgId, "admin_api_role_batchdelete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("角色绑定菜单页面", "显示角色绑定菜单页面", $roleMgId, "admin_role_bindmenu", "", 6, $roleId, 1, 1, 0);
        $this->addMenu("角色绑定菜单", "角色绑定菜单处理", $roleMgId, "admin_api_role_bindmenu", "", 7, $roleId, 1, 1, 0);
        //用户
        $userMgId = $this->addMenu("用户管理", "管理用户", $sysMenuId, "admin_user_index", "", 2, $roleId, 1, 0, 1);
        $this->addMenu("添加页面", "显示添加用户页面", $userMgId, "admin_user_add", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加用户", "添加用户处理", $userMgId, "admin_api_user_add", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("查看用户页面", "显示用户页面", $userMgId, "admin_user_view", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("编辑用户页面", "显示编辑用户页面", $userMgId, "admin_user_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑用户", "编辑用户处理", $userMgId, "admin_api_user_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除用户", "删除用户处理", $userMgId, "admin_api_user_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("重置用户密码", "重置用户密码", $userMgId, "admin_user_resetPwd", "", 6, $roleId, 1, 1, 0);
        $this->addMenu("批量删除用户", "批量删除用户处理", $userMgId, "admin_api_user_bathdelete", "", 7, $roleId, 1, 1, 0);
        $this->addMenu("锁定/解锁用户", "锁定/解锁用户", $userMgId, "admin_api_user_switchLock", "", 8, $roleId, 1, 1, 0);
        //操作日志
        $adminlogMgId = $this->addMenu("操作日志", "操作日志", $sysMenuId, "admin_adminlog_index", "", 3, $roleId, 1, 0, 1);

        $optionMgId = $this->addMenu("配置", "对系统的相关配置", $sysMenuId, "admin_option_index", "", 0, $roleId, 1, 0, 1);
        $this->addMenu("添加页面", "添加配置页面展示", $optionMgId, "admin_option_add", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("添加", "添加配置处理", $optionMgId, "admin_api_option_add", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("编辑页面", "编辑配置页面展示", $optionMgId, "admin_option_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑", "编辑配置处理", $optionMgId, "admin_api_option_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除", "删除配置处理", $optionMgId, "admin_api_option_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("批量删除", "批量删除配置处理", $optionMgId, "admin_api_option_bathdelete", "", 6, $roleId, 1, 1, 0);
        $this->addMenu("清空缓存", "清空缓存", $optionMgId, "admin_option_clear_cache", "", 7, $roleId, 1, 1, 0);
        //字典管理
        $dictMgId = $this->addMenu("字典管理", "字典管理相关", $sysMenuId, "admin_dict_type_index", "", 1, $roleId, 1, 0, 1);
        $this->addMenu("添加页面", "添加字典页面展示", $dictMgId, "admin_dict_type_add", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("添加", "添加字典处理", $dictMgId, "admin_api_dict_type_add", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("编辑页面", "编辑字典页面展示", $dictMgId, "admin_dict_type_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑", "编辑字典处理", $dictMgId, "admin_api_dict_type_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除", "删除配置处理", $dictMgId, "admin_api_dict_type_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("批量删除", "批量删除字典处理", $dictMgId, "admin_api_dict_type_bathdelete", "", 6, $roleId, 1, 1, 0);
        $this->addMenu("开启/关闭字典", "开启/关闭字典", $dictMgId, "admin_api_dict_type_switch_status", "", 7, $roleId, 1, 1, 0);
        $this->addMenu("查看", "字典查看", $dictMgId, "admin_dict_type_view", "", 8, $roleId, 1, 1, 0);
        $this->addMenu("清空缓存", "清空字典缓存", $dictMgId, "admin_dict_type_clear_cache", "", 9, $roleId, 1, 1, 0);
        //字典数据管理
        $dictMgId = $this->addMenu("字典数据管理", "字典数据管理相关", $dictMgId, "admin_dict_data_index", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加页面", "添加字典数据页面展示", $dictMgId, "admin_dict_data_add", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("添加", "添加字典数据处理", $dictMgId, "admin_api_dict_data_add", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("编辑页面", "编辑字典数据页面展示", $dictMgId, "admin_dict_data_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑", "编辑字典数据处理", $dictMgId, "admin_api_dict_data_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除", "删除配置数据处理", $dictMgId, "admin_api_dict_data_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("批量删除", "批量删除字典数据处理", $dictMgId, "admin_api_dict_data_bathdelete", "", 6, $roleId, 1, 1, 0);
        $this->addMenu("开启/关闭字典数据", "开启/关闭字典数据", $dictMgId, "admin_api_dict_data_switch_status", "", 7, $roleId, 1, 1, 0);
        $this->addMenu("查看", "字典数据查看", $dictMgId, "admin_dict_data_view", "", 8, $roleId, 1, 1, 0);

        $scheduleMgId = $this->addMenu("计划任务管理", "计划任务管理", $sysMenuId, "admin_schedule_index", "", 3, $roleId, 1, 0, 1);
        $this->addMenu("开启/关闭", "开启/关闭", $scheduleMgId, "admin_api_schedule_switch_status", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("导入任务", "导入任务", $scheduleMgId, "admin_schedule_import", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("任务日志", "任务日志", $scheduleMgId, "admin_schedule_log_index", "", 3, $roleId, 1, 1, 0);


    }

    protected function addMenu($name, $descr, $pid, $uri, $style, $sort, $roleId, $isLock, $isAccess, $isShow, $isGlobal = 0)
    {
        $menuModel = new BaseMenu();
        $menuModel->setName($name);
        $menuModel->setDescr($descr);
        $menuModel->setUrl($uri);
        $menuModel->setIsLock($isLock);
        $menuModel->setIsAccess($isAccess);
        $menuModel->setIsShow($isShow);
        $menuModel->setPid($pid);
        $menuModel->setSort($sort);
        $menuModel->setStyle($style);
        $menuModel->setIsGlobal($isGlobal);
        $this->baseService->getDoctrine()->getManager()->persist($menuModel);
        $this->baseService->getDoctrine()->getManager()->flush();
        $menuId = $menuModel->getId();
        $roleMenuModel = new BaseRoleMenu();
        $roleMenuModel->setRoleId($roleId);
        $roleMenuModel->setMenuId($menuId);
        $this->baseService->getDoctrine()->getManager()->persist($roleMenuModel);
        $this->baseService->getDoctrine()->getManager()->flush();
        return $menuId;
    }
}
