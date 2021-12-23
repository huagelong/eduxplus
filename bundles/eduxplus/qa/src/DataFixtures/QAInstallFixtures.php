<?php

namespace Eduxplus\QaBundle\DataFixtures;

use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Eduxplus\CoreBundle\Entity\BaseMenu;
use Eduxplus\CoreBundle\Entity\BaseOption;
use Eduxplus\CoreBundle\Entity\BaseRole;
use Eduxplus\CoreBundle\Entity\BaseRoleMenu;
use Eduxplus\CoreBundle\Entity\BaseRoleUser;
use Eduxplus\CoreBundle\Entity\BaseUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QAInstallFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    protected $manager;
    protected $helperService;

    public function __construct(
        HelperService $helperService
    ) {
        $this->helperService = $helperService;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        //        -----网站基础配置---
//        $this->addOption("app.question.version", "v1.0.0", "题库版本", 1, 1, "题库配置");

        //菜单
        $roleId = 1;

        //qa_admin_chapter_searchChapter
        $this->addMenu("搜索章节点集合", "搜索章节点集合", 0, "qa_admin_chapter_searchChapter", "", 3, $roleId, 1, 1, 0, 1);
        $this->addMenu("获取章节点树节点", "获取章节点树节点", 0, "qa_admin_chaptersub_getChapterSub_do", "", 3, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索试卷产品", "搜索试卷产品", 0, "admin_qa_api_glob_searchProductDo", "", 3, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索试卷商品", "搜索试卷商品", 0, "admin_qa_api_glob_searchGoodsDo", "", 3, $roleId, 1, 1, 0, 1);


        $menuId = $this->addMenu("题库", "题库方面的管理", 0, "", "fab fa-audible", 20, $roleId, 0, 0, 1);

        $mgId = $this->addMenu("试题管理", "试题管理", $menuId, "qa_admin_chapter_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("章节点集合添加", "展示章节点集合添加页面", $mgId, "qa_admin_chapter_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("章节点集合添加处理", "章节点集合添加处理", $mgId, "qa_admin_chapter_do_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("章节点集合编辑", "展示章节点集合编辑页面", $mgId, "qa_admin_chapter_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("章节点集合编辑处理", "章节点集合编辑处理", $mgId, "qa_admin_chapter_do_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("章节点集合单个删除", "章节点集合单个删除", $mgId, "qa_admin_chapter_delete", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("章节点集合批量删除", "章节点集合批量删除", $mgId, "qa_admin_chapter_bathdelete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("章节点集合状态切换", "章节点集合状态切换", $mgId, "qa_admin_chapter_switchStatus", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("章节点管理", "章节点管理", $mgId, "qa_admin_chaptersub_index", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("章节点添加处理", "章节点添加处理", $mgId, "qa_admin_chaptersub_adddo", "", 8, $roleId, 0, 1, 0);
        $this->addMenu("章节点编辑展示", "章节点编辑展示", $mgId, "qa_admin_chaptersub_edit", "", 9, $roleId, 0, 1, 0);
        $this->addMenu("章节点编辑处理", "章节点编辑处理", $mgId, "qa_admin_chaptersub_edit_do", "", 10, $roleId, 0, 1, 0);
        $this->addMenu("章节点删除", "章节点删除", $mgId, "qa_admin_chaptersub_delete_do", "", 11, $roleId, 0, 1, 0);
        $this->addMenu("章节点保存排序", "章节点保存排序", $mgId, "qa_admin_chaptersub_updateSort", "", 12, $roleId, 0, 1, 0);
        $this->addMenu("试题管理", "管理试题节点", $mgId, "qa_admin_node_index", "", 13, $roleId, 0, 1, 0);
        $this->addMenu("试题查看", "试题查看", $mgId, "qa_admin_node_view", "", 14, $roleId, 0, 1, 0);
        $this->addMenu("试题添加", "试题添加", $mgId, "qa_admin_node_add", "", 15, $roleId, 0, 1, 0);
        $this->addMenu("试题添加处理", "试题添加处理", $mgId, "qa_admin_node_do_add", "", 16, $roleId, 0, 1, 0);
        $this->addMenu("试题编辑", "试题编辑", $mgId, "qa_admin_node_edit", "", 17, $roleId, 0, 1, 0);
        $this->addMenu("试题编辑处理", "试题编辑处理", $mgId, "qa_admin_node_do_edit", "", 18, $roleId, 0, 1, 0);
        $this->addMenu("试题删除", "试题删除", $mgId, "qa_admin_node_delete", "", 19, $roleId, 0, 1, 0);
        $this->addMenu("试题批量删除", "试题批量删除", $mgId, "qa_admin_node_bathdelete", "", 20, $roleId, 0, 1, 0);
        $this->addMenu("试题发布", "试题发布", $mgId, "qa_admin_node_switchStatus", "", 21, $roleId, 0, 1, 0);

        $mgId = $this->addMenu("试卷管理", "试卷管理", $menuId, "qa_admin_test_index", "", 1, $roleId, 0, 0, 1);
        $this->addMenu("试卷添加", "试卷添加", $mgId, "qa_admin_test_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("试卷添加处理", "试卷添加处理", $mgId, "qa_admin_test_do_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("试卷编辑", "试卷编辑", $mgId, "qa_admin_test_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("试卷编辑处理", "试卷编辑处理", $mgId, "qa_admin_test_do_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("试卷删除", "试卷删除", $mgId, "qa_admin_test_delete", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("试卷批量删除", "试卷批量删除", $mgId, "qa_admin_test_bathdelete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("试卷发布", "试卷发布", $mgId, "qa_admin_test_switchStatus", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("试卷预览", "试卷预览", $mgId, "qa_admin_test_preview", "", 7, $roleId, 0, 1, 0);

        $this->addMenu("试题管理", "试题管理", $mgId, "qa_admin_test_sub_index", "", 8, $roleId, 0, 1, 0);
        $this->addMenu("试题管理", "试题管理", $mgId, "qa_admin_test_sub_mg", "", 9, $roleId, 0, 1, 0);
        $this->addMenu("试题添加处理", "试题添加处理", $mgId, "qa_admin_test_sub_do_mg", "", 10, $roleId, 0, 1, 0);
        $this->addMenu("试题编辑", "试题编辑", $mgId, "qa_admin_test_sub_edit", "", 11, $roleId, 0, 1, 0);
        $this->addMenu("试题编辑处理", "试题编辑处理", $mgId, "qa_admin_test_sub_do_edit", "", 12, $roleId, 0, 1, 0);
        $this->addMenu("试题删除", "试题删除", $mgId, "qa_admin_test_sub_delete", "", 13, $roleId, 0, 1, 0);
        $this->addMenu("试题批量删除", "试题批量删除", $mgId, "qa_admin_test_sub_bathdelete", "", 14, $roleId, 0, 1, 0);

        $mgId = $this->addMenu("试卷商品管理", "试卷商品管理", $menuId, "admin_qa_mall_goods_index", "", 2, $roleId, 0, 0, 1);
        $this->addMenu("查看单个试卷商品信息", "查看单个试卷商品信息", $mgId, "admin_qa_mall_goods_view", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("查看组合试卷商品信息", "查看组合试卷商品信息", $mgId, "admin_qa_mall_goods_viewgroup", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加单个试卷商品页面", "添加单个试卷商品页面展示", $mgId, "admin_qa_mall_goods_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("添加组合试卷商品页面", "添加组合试卷商品页面展示", $mgId, "admin_qa_mall_group_goods_add", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("添加试卷商品处理", "添加试卷商品处理", $mgId, "admin_qa_api_mall_goods_add", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑单个试卷商品页面", "编辑单个试卷商品页面展示", $mgId, "admin_qa_mall_goods_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("编辑组合试卷商品页面", "编辑组合试卷商品页面展示", $mgId, "admin_qa_mall_goods_editgroup", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("编辑试卷商品处理", "编辑试卷商品处理", $mgId, "admin_qa_api_mall_goods_edit", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("删除试卷商品", "删除试卷商品处理", $mgId, "admin_qa_api_mall_goods_delete", "", 8, $roleId, 0, 1, 0);
        $this->addMenu("批量删除试卷商品", "批量删除试卷商品处理", $mgId, "admin_qa_api_mall_goods_bathdelete", "", 9, $roleId, 0, 1, 0);
        $this->addMenu("试卷商品上下架", "试卷商品上下架", $mgId, "admin_qa_api_mall_goods_switchStatus", "", 10, $roleId, 0, 1, 0);
    }

    protected function addOption($key, $value, $descr, $type = 1, $isLock = 1, $group='')
    {
        $optionModel = new BaseOption();
        $optionModel->setOptionKey($key);
        $optionModel->setOptionValue($value);
        $optionModel->setDescr($descr);
        $optionModel->setIsLock($isLock);
        $optionModel->setOptionGroup($group);
        $optionModel->setType($type);
        $this->manager->persist($optionModel);
        $this->manager->flush();
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
        $this->manager->persist($menuModel);
        $this->manager->flush();
        $menuId = $menuModel->getId();
        $roleMenuModel = new BaseRoleMenu();
        $roleMenuModel->setRoleId($roleId);
        $roleMenuModel->setMenuId($menuId);
        $this->manager->persist($roleMenuModel);
        $this->manager->flush();
        return $menuId;
    }
}
