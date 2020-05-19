<?php

namespace App\DataFixtures;

use App\Bundle\AppBundle\Lib\Service\HelperService;
use App\Entity\BaseMenu;
use App\Entity\BaseOption;
use App\Entity\BaseRole;
use App\Entity\BaseRoleMenu;
use App\Entity\BaseRoleUser;
use App\Entity\BaseUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InstallFixtures extends Fixture
{
    protected $passwordEncoder;
    /**
     * @var ObjectManager
     */
    protected $manager;
    protected $helperService;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder
        ,HelperService $helperService
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->helperService = $helperService;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        //配置初始化
        $this->addOption("app.name", "多学课堂", "应用名称", 1, 1);
        $this->addOption("app.logo", '["/assets/images/logo.png"]', "应用logo", 2, 1);
        $this->addOption("app.user.default.gravatar", '["/assets/images/gravatar.jpeg"]', "用户默认头像", 2, 1);
        $this->addOption("app.icon", '["/assets/images/fav.png"]', "应用icon", 2, 1);
        $this->addOption("app.domain", 'http://www.eduxplus.test/', "应用域名网址", 1, 1);
        $this->addOption("app.bokecc.key", '1xaBDOtW1a4SSanrqeqXjYlTj5D9gGgO', "获得场景视频点播key", 1, 1);
        $this->addOption("app.bokecc.uid", '401ABBE68F38B6DA', "获得场景视频账户ID", 1, 1);
        $this->addOption("app.upload.adapter", '2', "文件上传方式,1-本地，2-阿里云oss", 1, 1);
        $this->addOption("app.aliyun.accesskeyId", 'LTAI4G81aC1zgpkUsDXHdmVG', "阿里云 accesskeyId", 1, 1);
        $this->addOption("app.aliyun.accesskeySecret", 'ljeQEmQgXGo0oKfwZIMrn9r3rkN74o', "阿里云 accesskeySecret ", 1, 1);
        $this->addOption("app.aliyun.oss.bucket", 'eduxplus', "阿里云oss Bucket ", 1, 1);
        $this->addOption("app.aliyun.oss.endpoint", 'oss-cn-shanghai.aliyuncs.com', "阿里云oss endpoint ", 1, 1);
        $this->addOption("app.cdn.domain", '', "cdn域名", 1, 1);

        //初始化用户
        $userModel = new BaseUser();
        $uuid = $this->helperService->getUuid();
        $pwd = $this->passwordEncoder->encodePassword($userModel, "111111");
        $userModel->setSex(1);
        $userModel->setBirthday('1988-10-01');
        $userModel->setRegSource("web");
        $userModel->setMobile("17621487000");
        $userModel->setReportUid(0);
        $userModel->setUuid($uuid);
        $userModel->setDisplayName("超级管理员");
        $userModel->setFullName("管理员大大");
        $userModel->setIsAdmin(1);
        $userModel->setPassword($pwd);
        $userModel->setGravatar("/assets/images/gravatar.jpeg");
        $manager->persist($userModel);
        $manager->flush();
        $uid = $userModel->getId();

        //添加默认权限
        $roleModel = new BaseRole();
        $roleModel->setName("超级管理员");
        $roleModel->setDescr("超级管理员有所有权限");
        $roleModel->setIsLock(1);
        $manager->persist($roleModel);
        $manager->flush();
        $roleId = $roleModel->getId();

        //绑定用户角色
        $roleUserModel = new BaseRoleUser();
        $roleUserModel->setUid($uid);
        $roleUserModel->setRoleId($roleId);
        $manager->persist($roleUserModel);
        $manager->flush();

        //新增菜单并绑定角色
        $this->addMenu("首页","后台首页", 0,"admin_dashboard", "fas fa-home",0, $roleId, 1, 0, 1);
        $this->addMenu("文件上传","文件上传处理", 0,"admin_glob_upload", "fas fa-upload",1, $roleId, 1, 1, 0);
        $this->addMenu("搜索用户名","搜索用户名", 0,"admin_api_glob_searchUserDo", "",2, $roleId, 1, 1, 0);
        $this->addMenu("搜索产品","搜索产品", 0,"admin_api_glob_searchProductDo", "",3, $roleId, 1, 1, 0);
        $this->addMenu("搜索商品","搜索商品", 0,"admin_api_glob_searchGoodsDo", "",4, $roleId, 1, 1, 0);
        $this->addMenu("搜索课程","搜索课程", 0,"admin_api_glob_searchCourseDo", "",5, $roleId, 1, 1, 0);

        //安全模块
        $accMenuId = $this->addMenu("安全","安全方面的管理", 0,"", "fas fa-key",2, $roleId, 1, 0, 1);
        //菜单
        $menuMgId = $this->addMenu("菜单管理","管理菜单以及对应页面的权限", $accMenuId,"admin_menu_index", "",3, $roleId, 1, 0, 1);
        $this->addMenu("添加菜单页面","菜单新增页面", $menuMgId,"admin_menu_add", "",0, $roleId, 1, 1, 0);
        $this->addMenu("添加菜单","菜单新增处理", $menuMgId,"admin_api_menu_add", "",1, $roleId, 1, 1, 0);
        $this->addMenu("编辑菜单页面","菜单编辑展示页面", $menuMgId,"admin_menu_edit", "",2, $roleId, 1, 1, 0);
        $this->addMenu("编辑菜单","菜单编辑处理", $menuMgId,"admin_api_menu_edit", "",3, $roleId, 1, 1, 0);
        $this->addMenu("删除菜单","删除菜单", $menuMgId,"admin_api_menu_delete", "",4, $roleId, 1, 1, 0);
        $this->addMenu("更新菜单排序","更新菜单排序", $menuMgId,"admin_api_menu_updateSort", "",5, $roleId, 1, 1, 0);
        //角色
        $roleMgId = $this->addMenu("角色管理","管理角色", $accMenuId,"admin_role_index", "",1, $roleId, 1, 0, 1);
        $this->addMenu("添加角色页面","显示添加角色页面", $roleMgId,"admin_role_add", "",0, $roleId, 1, 1, 0);
        $this->addMenu("添加角色","添加角色处理", $roleMgId,"admin_api_role_add", "",1, $roleId, 1, 1, 0);
        $this->addMenu("编辑角色页面","显示编辑角色页面", $roleMgId,"admin_role_edit", "",2, $roleId, 1, 1, 0);
        $this->addMenu("编辑角色","编辑角色处理", $roleMgId,"admin_api_role_edit", "",3, $roleId, 1, 1, 0);
        $this->addMenu("删除角色","删除角色处理", $roleMgId,"admin_api_role_delete", "",4, $roleId, 1, 1, 0);
        $this->addMenu("角色绑定菜单页面","显示角色绑定菜单页面", $roleMgId,"admin_role_bindmenu", "",5, $roleId, 1, 1, 0);
        $this->addMenu("角色绑定菜单","角色绑定菜单处理", $roleMgId,"admin_api_role_bindmenu", "",6, $roleId, 1, 1, 0);
        //用户
        $userMgId = $this->addMenu("用户管理","管理用户", $accMenuId,"admin_user_index", "",2, $roleId, 1, 0, 1);
        $this->addMenu("添加页面","显示添加用户页面", $userMgId,"admin_user_add", "",0, $roleId, 1, 1, 0);
        $this->addMenu("添加用户","添加用户处理", $userMgId,"admin_api_user_add", "",1, $roleId, 1, 1, 0);
        $this->addMenu("编辑用户页面","显示编辑用户页面", $userMgId,"admin_user_edit", "",2, $roleId, 1, 1, 0);
        $this->addMenu("编辑用户","编辑用户处理", $userMgId,"admin_api_user_edit", "",3, $roleId, 1, 1, 0);
        $this->addMenu("删除用户","删除用户处理", $userMgId,"admin_api_user_delete", "",4, $roleId, 1, 1, 0);
        $this->addMenu("锁定/解锁用户","锁定/解锁用户", $userMgId,"admin_api_user_switchLock", "",5, $roleId, 1, 1, 0);
        //操作日志
        $adminlogMgId = $this->addMenu("操作日志","操作日志", $accMenuId,"admin_adminlog_index", "",3, $roleId, 1, 0, 1);
        //系统模块
        $sysMenuId = $this->addMenu("系统","系统方面的管理", 0,"", "fa fa-gears",3, $roleId, 1, 0, 1);
        $optionMgId = $this->addMenu("配置","对系统的相关配置", $sysMenuId,"admin_option_index", "",0, $roleId, 1, 0, 1);
        $this->addMenu("添加页面","添加配置页面展示", $optionMgId,"admin_option_add", "",3, $roleId, 1, 1, 0);
        $this->addMenu("添加","添加配置处理", $optionMgId,"admin_api_option_add", "",4, $roleId, 1, 1, 0);
        $this->addMenu("编辑页面","编辑配置页面展示", $optionMgId,"admin_option_edit", "",3, $roleId, 1, 1, 0);
        $this->addMenu("编辑","编辑配置处理", $optionMgId,"admin_api_option_edit", "",4, $roleId, 1, 1, 0);
        $this->addMenu("删除","删除配置处理", $optionMgId,"admin_api_option_delete", "",5, $roleId, 1, 1, 0);
        //教研
        $teachMenuId = $this->addMenu("教研","教学产品方面的管理", 0,"", "fa fa-bank",4, $roleId, 0, 0, 1);
        //协议
        $agreementMgId = $this->addMenu("协议管理","针对各种协议的管理", $teachMenuId,"admin_teach_agreement_index", "",0, $roleId, 0, 0, 1);
        $this->addMenu("添加页面","添加页面展示", $agreementMgId,"admin_teach_agreement_add", "",3, $roleId, 0, 1, 0);
        $this->addMenu("添加","添加处理", $agreementMgId,"admin_api_teach_agreement_add", "",4, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面","编辑页面展示", $agreementMgId,"admin_teach_agreement_edit", "",3, $roleId, 0, 1, 0);
        $this->addMenu("编辑","编辑处理", $agreementMgId,"admin_api_teach_agreement_edit", "",4, $roleId, 0, 1, 0);
        $this->addMenu("删除","删除处理", $agreementMgId,"admin_api_teach_agreement_delete", "",5, $roleId, 0, 1, 0);
        //分类
        $mgId = $this->addMenu("分类管理","分类的管理", $teachMenuId,"admin_teach_category_index", "",0, $roleId, 0, 0, 1);
        $this->addMenu("添加","添加处理", $mgId,"admin_api_teach_category_add", "",4, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面","编辑页面展示", $mgId,"admin_teach_category_edit", "",3, $roleId, 0, 1, 0);
        $this->addMenu("编辑","编辑处理", $mgId,"admin_api_teach_category_edit", "",5, $roleId, 0, 1, 0);
        $this->addMenu("删除","删除处理", $mgId,"admin_api_teach_category_delete", "",6, $roleId, 0, 1, 0);
        $this->addMenu("更新排序","更新排序", $mgId,"admin_api_teach_category_updateSort", "",7, $roleId, 0, 1, 0);
        //课程管理
        $mgId = $this->addMenu("课程管理","课程的管理", $teachMenuId,"admin_teach_course_index", "",0, $roleId, 0, 0, 1);
        $this->addMenu("添加页面","添加页面展示", $mgId,"admin_teach_course_add", "",0, $roleId, 0, 1, 0);
        $this->addMenu("添加","添加处理", $mgId,"admin_api_teach_course_add", "",1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面","编辑页面展示", $mgId,"admin_teach_course_edit", "",2, $roleId, 0, 1, 0);
        $this->addMenu("编辑","编辑处理", $mgId,"admin_api_teach_course_edit", "",3, $roleId, 0, 1, 0);
        $this->addMenu("删除","删除处理", $mgId,"admin_api_teach_course_delete", "",4, $roleId, 0, 1, 0);
        $this->addMenu("课程上下架","课程上下架", $mgId,"admin_api_teach_course_switchStatus", "",6, $roleId, 0, 1, 0);
        //章节管理
        $mgId = $this->addMenu("课程章节管理","课程章节管理", $mgId,"admin_teach_chapter_index", "",7, $roleId, 0, 1, 0);
        $this->addMenu("章节添加页面","章节添加页面展示", $mgId,"admin_teach_chapter_add", "",8, $roleId, 0, 1, 0);
        $this->addMenu("章节添加","添加处理", $mgId,"admin_api_teach_chapter_add", "",9, $roleId, 0, 1, 0);
        $this->addMenu("章节编辑页面","编辑页面展示", $mgId,"admin_teach_chapter_edit", "",10, $roleId, 0, 1, 0);
        $this->addMenu("章节编辑","编辑处理", $mgId,"admin_api_teach_chapter_edit", "",11, $roleId, 0, 1, 0);
        $this->addMenu("章节删除","删除处理", $mgId,"admin_api_teach_chapter_delete", "",12, $roleId, 0, 1, 0);
        $this->addMenu("章节更新排序","章节更新排序", $mgId,"admin_api_teach_chapter_updateSort", "",13, $roleId, 0, 1, 0);
        $this->addMenu("视频管理","管理视频", $mgId,"admin_teach_chapter_video", "",14, $roleId, 0, 1, 0);
        $this->addMenu("视频管理处理","视频添加、编辑等处理", $mgId,"admin_api_teach_chapter_video", "",14, $roleId, 0, 1, 0);
        $this->addMenu("附件管理","附件添加、编辑", $mgId,"admin_teach_chapter_materials", "",15, $roleId, 0, 1, 0);
        $this->addMenu("附件管理处理","附件添加、编辑等处理", $mgId,"admin_api_teach_chapter_materials", "",16, $roleId, 0, 1, 0);

        //产品管理
        $mgId = $this->addMenu("产品管理","产品的管理", $teachMenuId,"admin_teach_product_index", "",0, $roleId, 0, 0, 1);
        $this->addMenu("添加页面","添加页面展示", $mgId,"admin_teach_product_add", "",0, $roleId, 0, 1, 0);
        $this->addMenu("添加","添加处理", $mgId,"admin_api_teach_product_add", "",1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面","编辑页面展示", $mgId,"admin_teach_product_edit", "",2, $roleId, 0, 1, 0);
        $this->addMenu("编辑","编辑处理", $mgId,"admin_api_teach_product_edit", "",3, $roleId, 0, 1, 0);
        $this->addMenu("删除","删除处理", $mgId,"admin_api_teach_product_delete", "",4, $roleId, 0, 1, 0);
        $this->addMenu("产品上下架","产品上下架", $mgId,"admin_api_teach_product_switchStatus", "",6, $roleId, 0, 1, 0);
        $this->addMenu("是否自动选择学习计划","根据报名时间选择", $mgId,"admin_api_teach_product_switchPlanAutoStatus", "",6, $roleId, 0, 1, 0);
        //学习计划管理
        $mgId = $this->addMenu("学习计划管理","学习计划管理页面展示", $mgId,"admin_teach_studyplan_index", "",8, $roleId, 0, 1, 0);
        $this->addMenu("搜索课程","搜索课程", $mgId,"admin_api_teach_studyplan_searchCourseDo", "",0, $roleId, 0, 1, 0);
        $this->addMenu("添加页面","添加页面展示", $mgId,"admin_teach_studyplan_add", "",1, $roleId, 0, 1, 0);
        $this->addMenu("添加","添加处理", $mgId,"admin_api_teach_studyplan_add", "",2, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面","编辑页面展示", $mgId,"admin_teach_studyplan_edit", "",3, $roleId, 0, 1, 0);
        $this->addMenu("编辑","编辑处理", $mgId,"admin_api_teach_studyplan_edit", "",4, $roleId, 0, 1, 0);
        $this->addMenu("删除","删除处理", $mgId,"admin_api_teach_studyplan_delete", "",5, $roleId, 0, 1, 0);
        $this->addMenu("更新排序","更新排序", $mgId,"admin_api_teach_studyplan_updateSort", "",7, $roleId, 0, 1, 0);
        $this->addMenu("删除课程","删除课程处理", $mgId,"admin_api_teach_studyplansub_delete", "",8, $roleId, 0, 1, 0);
        //教务
        $jwMenuId = $this->addMenu("教务","教务方面的管理", 0,"", "fa fa-envira",5, $roleId, 0, 0, 1);
        //学校管理
        $mgId = $this->addMenu("校区管理","校区信息管理", $jwMenuId,"admin_jw_school_index", "",0, $roleId, 0, 0, 1);
        $this->addMenu("添加页面","添加页面展示", $mgId,"admin_jw_school_add", "",0, $roleId, 0, 1, 0);
        $this->addMenu("添加","添加处理", $mgId,"admin_api_jw_school_add", "",1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面","编辑页面展示", $mgId,"admin_jw_school_edit", "",2, $roleId, 0, 1, 0);
        $this->addMenu("编辑","编辑处理", $mgId,"admin_api_jw_school_edit", "",3, $roleId, 0, 1, 0);
        $this->addMenu("删除","删除处理", $mgId,"admin_api_jw_school_delete", "",4, $roleId, 0, 1, 0);
        //老师管理
        $mgId = $this->addMenu("老师管理","老师信息管理", $jwMenuId,"admin_jw_teacher_index", "",0, $roleId, 0, 0, 1);
        $this->addMenu("添加页面","添加页面展示", $mgId,"admin_jw_teacher_add", "",0, $roleId, 0, 1, 0);
        $this->addMenu("添加","添加处理", $mgId,"admin_api_jw_teacher_add", "",1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面","编辑页面展示", $mgId,"admin_jw_teacher_edit", "",2, $roleId, 0, 1, 0);
        $this->addMenu("编辑","编辑处理", $mgId,"admin_api_jw_teacher_edit", "",3, $roleId, 0, 1, 0);
        $this->addMenu("删除","删除处理", $mgId,"admin_api_jw_teacher_delete", "",4, $roleId, 0, 1, 0);
        $this->addMenu("锁定/解锁老师","锁定/解锁老师", $userMgId,"admin_api_jw_teacher_switchStatus", "",5, $roleId, 0, 1, 0);

        //商城
        $mallMenuId = $this->addMenu("商城","商城方面的管理", 0,"", "fa fa-shopping-cart",6, $roleId, 0, 0, 1);
        //商品管理
        $mgId = $this->addMenu("商品管理","商品信息管理", $mallMenuId,"admin_mall_goods_index", "",0, $roleId, 0, 0, 1);
        $this->addMenu("添加页面","添加页面展示", $mgId,"admin_mall_goods_add", "",0, $roleId, 0, 1, 0);
        $this->addMenu("添加","添加处理", $mgId,"admin_api_mall_goods_add", "",1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面","编辑页面展示", $mgId,"admin_mall_goods_edit", "",2, $roleId, 0, 1, 0);
        $this->addMenu("编辑","编辑处理", $mgId,"admin_api_mall_goods_edit", "",3, $roleId, 0, 1, 0);
        $this->addMenu("删除","删除处理", $mgId,"admin_api_mall_goods_delete", "",4, $roleId, 0, 1, 0);
        $this->addMenu("商品上下架","商品上下架", $mgId,"admin_api_mall_goods_switchStatus", "",5, $roleId, 0, 1, 0);
        //优惠券管理
        $mgId = $this->addMenu("优惠券管理","优惠券信息管理", $mallMenuId,"admin_mall_coupon_index", "",1, $roleId, 0, 0, 1);
        $this->addMenu("添加页面","添加页面展示", $mgId,"admin_mall_coupon_add", "",0, $roleId, 0, 1, 0);
        $this->addMenu("添加","添加处理", $mgId,"admin_api_mall_coupon_add", "",1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面","编辑页面展示", $mgId,"admin_mall_coupon_edit", "",2, $roleId, 0, 1, 0);
        $this->addMenu("编辑","编辑处理", $mgId,"admin_api_mall_coupon_edit", "",3, $roleId, 0, 1, 0);
        $this->addMenu("删除","删除处理", $mgId,"admin_api_mall_coupon_delete", "",4, $roleId, 0, 1, 0);
        $this->addMenu("优惠券上下架","优惠券上下架", $mgId,"admin_api_mall_coupon_switchStatus", "",5, $roleId, 0, 1, 0);
        $this->addMenu("优惠码管理","优惠码管理", $mgId,"admin_mall_couponsub_index", "",6, $roleId, 0, 1, 0);
        $this->addMenu("优惠码导出","优惠码导出", $mgId,"admin_mall_couponsub_export", "",7, $roleId, 0, 1, 0);
        $this->addMenu("优惠码生成","优惠码生成", $mgId,"admin_mall_couponsub_create", "",8, $roleId, 0, 1, 0);
        //订单管理
        $orderId = $this->addMenu("订单管理","订单信息管理", $mallMenuId,"admin_mall_order_index", "",2, $roleId, 0, 0, 1);
    }

    protected function addOption($key, $value, $descr, $type=1, $isLock=1){
        $optionModel = new BaseOption();
        $optionModel->setOptionKey($key);
        $optionModel->setOptionValue($value);
        $optionModel->setDescr($descr);
        $optionModel->setIsLock($isLock);
        $optionModel->setType($type);
        $this->manager->persist($optionModel);
        $this->manager->flush();
    }

    protected function addMenu($name, $descr, $pid, $uri, $style,$sort,$roleId, $isLock, $isAccess, $isShow){
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
