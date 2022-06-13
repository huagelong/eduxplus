<?php

namespace Eduxplus\EduxBundle\DataFixtures\Fixtures;

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

        $this->addMenu("搜索产品", "搜索产品", 0, "admin_api_glob_searchProductDo", "", 4, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索商品", "搜索商品", 0, "admin_api_glob_searchGoodsDo", "", 5, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索课程", "搜索课程", 0, "admin_api_glob_searchCourseDo", "", 6, $roleId, 1, 1, 0, 1);
        
        //教学
        $teachMenuId = $this->addMenu("教学", "教学方面的管理", 0, "", "mdi mdi-school", 4, $roleId, 0, 0, 1);

        //分类
        $mgId = $this->addMenu("分类管理", "分类的管理", $teachMenuId, "admin_teach_category_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_teach_category_add", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_teach_category_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_teach_category_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_teach_category_delete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("更新排序", "更新排序", $mgId, "admin_api_teach_category_updateSort", "", 7, $roleId, 0, 1, 0);
        //课程管理
        $mgId = $this->addMenu("课程管理", "课程的管理", $teachMenuId, "admin_teach_course_index", "", 1, $roleId, 0, 0, 1);
        $this->addMenu("添加页面", "添加页面展示", $mgId, "admin_teach_course_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_teach_course_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_teach_course_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_teach_course_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_teach_course_delete", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_teach_course_bathdelete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("课程上下架", "课程上下架", $mgId, "admin_api_teach_course_switchStatus", "", 6, $roleId, 0, 1, 0);
        //直播课表
        $mgId = $this->addMenu("直播课表", "直播课表", $teachMenuId, "admin_teach_chapter_liveTable", "", 2, $roleId, 0, 0, 1);
        //协议
        $agreementMgId = $this->addMenu("协议管理", "针对各种协议的管理", $teachMenuId, "admin_teach_agreement_index", "", 3, $roleId, 0, 0, 1);
        $this->addMenu("查看", "查看", $agreementMgId, "admin_teach_agreement_view", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加页面", "添加页面展示", $agreementMgId, "admin_teach_agreement_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $agreementMgId, "admin_api_teach_agreement_add", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $agreementMgId, "admin_teach_agreement_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $agreementMgId, "admin_api_teach_agreement_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $agreementMgId, "admin_api_teach_agreement_delete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $agreementMgId, "admin_api_teach_agreement_bathdelete", "", 7, $roleId, 0, 1, 0);
        //章节管理
        $mgId = $this->addMenu("课程章节管理", "课程章节管理", $mgId, "admin_teach_chapter_index", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("章节添加页面", "章节添加页面展示", $mgId, "admin_teach_chapter_add", "", 8, $roleId, 0, 1, 0);
        $this->addMenu("章节添加", "添加处理", $mgId, "admin_api_teach_chapter_add", "", 9, $roleId, 0, 1, 0);
        $this->addMenu("章节编辑页面", "编辑页面展示", $mgId, "admin_teach_chapter_edit", "", 10, $roleId, 0, 1, 0);
        $this->addMenu("章节编辑", "编辑处理", $mgId, "admin_api_teach_chapter_edit", "", 11, $roleId, 0, 1, 0);
        $this->addMenu("章节删除", "删除处理", $mgId, "admin_api_teach_chapter_delete", "", 12, $roleId, 0, 1, 0);
        $this->addMenu("章节更新排序", "章节更新排序", $mgId, "admin_api_teach_chapter_updateSort", "", 13, $roleId, 0, 1, 0);
        $this->addMenu("点播", "管理点播", $mgId, "admin_teach_chapter_vod", "", 14, $roleId, 0, 1, 0);
        $this->addMenu("点播管理处理", "点播添加、编辑等处理", $mgId, "admin_api_teach_chapter_vod", "", 15, $roleId, 0, 1, 0);
        $this->addMenu("附件管理", "附件添加、编辑", $mgId, "admin_teach_chapter_materials", "", 16, $roleId, 0, 1, 0);
        $this->addMenu("附件管理处理", "附件添加、编辑等处理", $mgId, "admin_api_teach_chapter_materials", "", 17, $roleId, 0, 1, 0);
        $this->addMenu("直播", "管理直播", $mgId, "admin_teach_chapter_live", "", 18, $roleId, 0, 1, 0);
        $this->addMenu("直播预览", "直播预览", $mgId, "admin_teach_chapter_liveView", "", 19, $roleId, 0, 1, 0);
        $this->addMenu("直播管理处理", "直播添加、编辑等处理", $mgId, "admin_api_teach_chapter_live", "", 20, $roleId, 0, 1, 0);

        //产品管理
        $mgId = $this->addMenu("产品管理", "产品的管理", $teachMenuId, "admin_teach_product_index", "", 4, $roleId, 0, 0, 1);
        $this->addMenu("添加页面", "添加页面展示", $mgId, "admin_teach_product_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_teach_product_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_teach_product_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_teach_product_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_teach_product_delete", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_teach_product_bathdelete", "", 5, $roleId, 0, 1, 0);
        //
        $this->addMenu("产品上下架", "产品上下架", $mgId, "admin_api_teach_product_switchStatus", "", 6, $roleId, 0, 1, 0);
        //开课计划管理
        $mgId = $this->addMenu("开课计划管理", "开课计划管理页面展示", $mgId, "admin_teach_studyplan_index", "", 8, $roleId, 0, 1, 0);
        $this->addMenu("搜索课程", "搜索课程", $mgId, "admin_api_teach_studyplan_searchCourseDo", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("添加页面", "添加页面展示", $mgId, "admin_teach_studyplan_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_teach_studyplan_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_teach_studyplan_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_teach_studyplan_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_teach_studyplan_delete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("更新排序", "更新排序", $mgId, "admin_api_teach_studyplan_updateSort", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("删除课程", "删除课程处理", $mgId, "admin_api_teach_studyplansub_delete", "", 8, $roleId, 0, 1, 0);
        $this->addMenu("开课计划上下架处理", "开课计划上下架处理", $mgId, "admin_api_teach_studyplan_switchStatus", "", 9, $roleId, 0, 1, 0);

        //教务
        $jwMenuId = $this->addMenu("教务", "教务方面的管理", $teachMenuId, "", "mdi mdi-teach", 5, $roleId, 0, 0, 1);
        //学校管理
        $mgId = $this->addMenu("校区管理", "校区信息管理", $jwMenuId, "admin_jw_school_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("查看", "查看", $mgId, "admin_jw_school_view", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("添加页面", "添加页面展示", $mgId, "admin_jw_school_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_jw_school_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_jw_school_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_jw_school_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_jw_school_delete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_jw_school_bathdelete", "", 6, $roleId, 0, 1, 0);
        //老师管理
        $mgId = $this->addMenu("老师管理", "老师信息管理", $jwMenuId, "admin_jw_teacher_index", "", 1, $roleId, 0, 0, 1);
        $this->addMenu("查看老师信息", "查看老师信息", $mgId, "admin_jw_teacher_view", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("添加页面", "添加页面展示", $mgId, "admin_jw_teacher_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_jw_teacher_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_jw_teacher_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_jw_teacher_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_jw_teacher_delete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_jw_teacher_bathdelete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("锁定/解锁老师", "锁定/解锁老师", $mgId, "admin_api_jw_teacher_switchStatus", "", 7, $roleId, 0, 1, 0);
        //班级管理
        $mgId = $this->addMenu("班级管理", "班级管理", $jwMenuId, "admin_jw_class_index", "", 2, $roleId, 0, 0, 1);
        $this->addMenu("学员管理", "学员管理", $mgId, "admin_jw_class_members", "", 0, $roleId, 0, 1, 0);
        //商城
        $mallMenuId = $this->addMenu("商城", "商城方面的管理", 0, "", "mdi mdi-cart", 6, $roleId, 0, 0, 1);
        //商品管理
        $mgId = $this->addMenu("商品管理", "商品信息管理", $mallMenuId, "admin_mall_goods_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("查看单个商品信息", "查看单个商品信息", $mgId, "admin_mall_goods_view", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("查看组合商品信息", "查看组合商品信息", $mgId, "admin_mall_goods_viewgroup", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加单个商品页面", "添加单个商品页面展示", $mgId, "admin_mall_goods_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("添加组合商品页面", "添加组合商品页面展示", $mgId, "admin_mall_group_goods_add", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_mall_goods_add", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑单个商品页面", "编辑单个商品页面展示", $mgId, "admin_mall_goods_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("编辑组合商品页面", "编辑组合商品页面展示", $mgId, "admin_mall_goods_editgroup", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_mall_goods_edit", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_mall_goods_delete", "", 8, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_mall_goods_bathdelete", "", 9, $roleId, 0, 1, 0);
        $this->addMenu("商品上下架", "商品上下架", $mgId, "admin_api_mall_goods_switchStatus", "", 10, $roleId, 0, 1, 0);
        //优惠券管理
        $mgId = $this->addMenu("优惠券管理", "优惠券信息管理", $mallMenuId, "admin_mall_coupon_index", "", 1, $roleId, 0, 0, 1);
        $this->addMenu("添加页面", "添加页面展示", $mgId, "admin_mall_coupon_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_mall_coupon_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_mall_coupon_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_mall_coupon_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_mall_coupon_delete", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_mall_coupon_bathdelete", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("优惠券上下架", "优惠券上下架", $mgId, "admin_api_mall_coupon_switchStatus", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("优惠码管理", "优惠码管理", $mgId, "admin_mall_couponsub_index", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("优惠码导出", "优惠码导出", $mgId, "admin_mall_couponsub_export", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("优惠码生成", "优惠码生成", $mgId, "admin_mall_couponsub_create", "", 8, $roleId, 0, 1, 0);
        //订单管理
        $orderId = $this->addMenu("订单管理", "订单信息管理", $mallMenuId, "admin_mall_order_index", "", 2, $roleId, 0, 0, 1);
        //支付管理
        $payId = $this->addMenu("支付管理", "支付信息管理", $mallMenuId, "admin_mall_pay_index", "", 3, $roleId, 0, 0, 1);
        //banner管理
        $mgId = $this->addMenu("banner管理", "banner管理", $mallMenuId, "admin_mall_banner_index", "", 4, $roleId, 0, 0, 1);
        $this->addMenu("添加", "添加展示", $mgId, "admin_mall_banner_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_mall_banner_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面展示", "编辑展示", $mgId, "admin_mall_banner_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_mall_banner_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("删除", "单个删除处理", $mgId, "admin_api_mall_banner_delete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_mall_banner_bathdelete", "", 6, $roleId, 0, 1, 0);

        $this->addMenu("单个banner列表", "单个banner列表", $mgId, "admin_mall_bannermain_index", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("添加单个banner", "添加单个banner展示", $mgId, "admin_mall_bannermain_add", "", 8, $roleId, 0, 1, 0);
        $this->addMenu("添加单个banner处理", "添加单个banner处理", $mgId, "admin_api_mall_bannermain_add", "", 9, $roleId, 0, 1, 0);
        $this->addMenu("编辑单个banner页面展示", "编辑单个banner展示", $mgId, "admin_mall_bannermain_edit", "", 10, $roleId, 0, 1, 0);
        $this->addMenu("编辑单个banner处理", "编辑单个banner处理", $mgId, "admin_api_mall_bannermain_edit", "", 11, $roleId, 0, 1, 0);
        $this->addMenu("删除单个banner", "删除单个banner处理", $mgId, "admin_api_mall_bannermain_delete", "", 12, $roleId, 0, 1, 0);
        $this->addMenu("批量删除单个banner", "批量删除单个banner处理", $mgId, "admin_api_mall_bannermain_bathdelete", "", 13, $roleId, 0, 1, 0);
        $this->addMenu("单个banner上下架", "单个banner上下架", $mgId, "admin_api_mall_bannermain_switchStatus", "", 14, $roleId, 0, 1, 0);

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
