<?php

namespace Eduxplus\CmsBundle\DataFixtures\Fixtures;

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
        $cmsMenuId = $this->addMenu("CMS", "内容管理系统", 0, "", "mdi mdi-book", 9, $roleId, 0, 0, 1);
        //单页管理
        $mgId = $this->addMenu("单页管理", "单页信息管理", $cmsMenuId, "admin_cms_page_index", "", 4, $roleId, 0, 0, 1);
        $this->addMenu("查看", "查看", $mgId, "admin_cms_page_view", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("添加页面", "添加页面展示", $mgId, "admin_cms_page_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_cms_page_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_cms_page_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_cms_page_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_cms_page_delete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_cms_page_bathdelete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("单页上下架", "单页上下架", $mgId, "admin_api_cms_page_switchStatus", "", 7, $roleId, 0, 1, 0);
        //帮助中心
        $helpmgId = $this->addMenu("帮助管理", "帮助管理", $cmsMenuId, "", "", 5, $roleId, 0, 0, 0);
        $mgId = $this->addMenu("帮助列表", "帮助列表", $helpmgId, "admin_cms_help_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("添加", "添加展示", $mgId, "admin_cms_help_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_cms_help_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("查看", "查看", $mgId, "admin_cms_help_view", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面展示", "编辑展示", $mgId, "admin_cms_help_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_cms_help_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("删除", "单个删除处理", $mgId, "admin_api_cms_help_delete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_cms_help_bathdelete", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("帮助上下架", "帮助上下架处理", $mgId, "admin_api_cms_help_switchStatus", "", 8, $roleId, 0, 1, 0);
        //帮助分类
        $mgId = $this->addMenu("帮助分类", "帮助分类", $helpmgId, "admin_cms_help_category_index", "", 8, $roleId, 0, 0, 1);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_cms_help_category_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面展示", "编辑页面展示", $mgId, "admin_cms_help_category_edit", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_cms_help_category_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("删除", "单个删除处理", $mgId, "admin_api_cms_help_category_delete", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("更新排序", "更新排序处理", $mgId, "admin_api_cms_help_category_updateSort", "", 4, $roleId, 0, 1, 0);

        //资讯管理
        $newsmgId = $this->addMenu("资讯管理", "资讯管理", $cmsMenuId, "", "", 5, $roleId, 0, 0, 1);
        $mgId = $this->addMenu("资讯列表", "资讯列表", $newsmgId, "admin_cms_news_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("添加", "添加展示", $mgId, "admin_cms_news_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_cms_news_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("查看", "查看", $mgId, "admin_cms_news_view", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面展示", "编辑展示", $mgId, "admin_cms_news_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_cms_news_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("删除", "单个删除处理", $mgId, "admin_api_cms_news_delete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_cms_news_bathdelete", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("资讯上下架", "资讯上下架处理", $mgId, "admin_api_cms_news_switchStatus", "", 8, $roleId, 0, 1, 0);
        //资讯分类
        $mgId = $this->addMenu("资讯分类", "资讯分类", $newsmgId, "admin_cms_news_category_index", "", 8, $roleId, 0, 0, 1);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_cms_news_category_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面展示", "编辑页面展示", $mgId, "admin_cms_news_category_edit", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_cms_news_category_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("删除", "单个删除处理", $mgId, "admin_api_cms_news_category_delete", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("更新排序", "更新排序处理", $mgId, "admin_api_cms_news_category_updateSort", "", 4, $roleId, 0, 1, 0);
        //公开课

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
