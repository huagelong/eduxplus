<?php

namespace Eduxplus\CoreBundle\DataFixtures;

use Eduxplus\CoreBundle\Lib\Service\Base\EsService;
use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Eduxplus\CoreBundle\Entity\BaseMenu;
use Eduxplus\CoreBundle\Entity\BaseOption;
use Eduxplus\CoreBundle\Entity\BaseRole;
use Eduxplus\CoreBundle\Entity\BaseRoleMenu;
use Eduxplus\CoreBundle\Entity\BaseRoleUser;
use Eduxplus\CoreBundle\Entity\BaseUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;
use Eduxplus\CoreBundle\Lib\Service\Base\MobileMaskService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InstallFixtures extends Fixture
{
    protected $passwordEncoder;
    /**
     * @var ObjectManager
     */
    protected $manager;
    protected $helperService;
    protected $mobileMaskService;
    protected $adminUserMobile;
    protected $adminUserPwd;

    public function __construct(
        UserPasswordHasherInterface $passwordEncoder,
        HelperService $helperService,
        MobileMaskService $mobileMaskService,
        string $adminUserMobile,
        string $adminUserPwd
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->helperService = $helperService;
        $this->mobileMaskService = $mobileMaskService;
        $this->adminUserMobile = $adminUserMobile;
        $this->adminUserPwd = $adminUserPwd;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        //        -----网站基础配置---
        $this->addOption("app.name", "eduxplus课堂", "网站名称", 1, 1, "网站配置");
        $this->addOption("app.cdn.domain", '', "cdn域名", 1, 1, "网站配置");
        $this->addOption("app.seo.title", '【eduxplus课堂官方网站】职业教育在线_移动学习、职达未来！', "全站seo标题", 1, 1, "网站配置");
        $this->addOption("app.seo.homepage.descr", '', "全站首页seo描述", 1, 1, "网站配置");
        $this->addOption("app.seo.homepage.keyword", '网校,职业教育在线,职业教育,职业教育在线', "首页seo关键字", 1, 1, "网站配置");
        $this->addOption("app.beian.number", '沪ICP备18036000号', "网站备案号", 1, 1, "网站配置");
        $this->addOption("app.copyright", '@2008-2019 上海xxxx有限公司版权所有', "网站copyright", 1, 1, "网站配置");
        $this->addOption("app.sms.times", '5', "当天准许同一手机发送短信的最大次数", 1, 1, "网站配置");
        $this->addOption("app.logo", '["/assets/images/logo.png"]', "网站logo", 2, 1, "网站配置");
        $this->addOption("app.user.default.gravatar", '["/assets/images/gravatar.jpeg"]', "用户默认头像", 2, 1, "网站配置");
        $this->addOption("app.icon", '["/assets/images/fav.png"]', "网站icon", 2, 1, "网站配置");
        $this->addOption("app.domain", 'http://dev.eduxplus.com/', "网站域名网址", 1, 1, "网站配置");
        //adapter
        $this->addOption("app.upload.adapter", '3', "文件上传方式,1-本地，2-阿里云oss，3-腾讯云cos", 1, 1, "网站配置");
        $this->addOption("app.vod.adapter", '1', "点播服务商，1-腾讯云，2-阿里云", 1, 1, "网站配置");
        $this->addOption("app.live.adapter", '1', "直播服务商，1-腾讯云，2-阿里云", 1, 1, "网站配置");
        $this->addOption("app.sms.adapter", '1', "短信服务商，1-腾讯云，2-阿里云", 1, 1, "网站配置");
        $this->addOption("app.search.adapter", '1', "搜索方式，1-本地数据库，2-elasticsearch", 1, 1, "网站配置");
        //        -------云服务基础配置---
        //腾讯云
        $this->addOption("app.tengxunyun.appId", '', "腾讯云 appId", 1, 1, "腾讯云基础配置");
        $this->addOption("app.tengxunyun.secretId", '', "腾讯云 accesskeyId", 1, 1, "腾讯云基础配置");
        $this->addOption("app.tengxunyun.secretKey", '', "腾讯云 accesskeySecret ", 1, 1, "腾讯云基础配置");
        $this->addOption("app.tengxunyun.region", 'ap-shanghai', "腾讯云地域接入域名", 1, 1, "腾讯云基础配置");

        //阿里云
        $this->addOption("app.aliyun.userId", '37030', "阿里云 账号id", 1, 1, "阿里云基础配置");
        $this->addOption("app.aliyun.accesskeyId", '', "阿里云 accesskeyId", 1, 1, "阿里云基础配置");
        $this->addOption("app.aliyun.accesskeySecret", '', "阿里云 accesskeySecret", 1, 1, "阿里云基础配置");
        //https://help.aliyun.com/document_detail/98194.html?spm=a2c4g.11186623.6.612.1fb73ecbvK52qE 点播中心和访问域名, 直播，点播都要在同一个地域
        $this->addOption("app.aliyun.region", 'cn-shenzhen', "阿里云地域接入域名", 1, 1, "阿里云基础配置");
//        ------短信配置---
        //腾讯云短信
        $this->addOption("app.tengxunyun.sms.appid", '', "腾讯云 短信应用id ", 1, 1, "腾讯云短信配置");
        $this->addOption("app.tengxunyun.sms.appkey", '', "腾讯云 短信应用key ", 1, 1, "腾讯云短信配置");
        $this->addOption("app.tengxunyun.sms.recaptcha.templateId", '516278', "腾讯云 验证码短信模板id ", 1, 1, "腾讯云短信配置");
        //腾讯云cos
        $this->addOption("app.tengxunyun.bucket", '', "腾讯云cos桶名称", 1, 1, "腾讯云COS存储配置");
        //腾讯云点播
        $this->addOption("app.tengxunyun.vod.encryptionkey", '', "腾讯云点播Key防盗链密钥", 1, 1, "腾讯云点播配置");
        $this->addOption("app.tengxunyun.vod.procedure", '', "腾讯云点播任务流模板名字", 1, 1, "腾讯云点播配置");
        //腾讯云直播
        $this->addOption("app.tengxunyun.live.pushDomain", '', "腾讯云直播推流域名", 1, 1, "腾讯云直播配置");
        $this->addOption("app.tengxunyun.live.playDomain", '', "腾讯云直播播放域名", 1, 1, "腾讯云直播配置");
        $this->addOption("app.tengxunyun.live.pushDomainKey", '', "腾讯云直播推流鉴权签名KEy", 1, 1, "腾讯云直播配置");
        $this->addOption("app.tengxunyun.live.playDomainKey", '', "腾讯云直播播流鉴权签名KEy", 1, 1, "腾讯云直播配置");
        $this->addOption("app.tengxunyun.live.transcodeTemplateId", '["default", "sd", "hd"]', "腾讯云直播转码模板名称", 1, 1, "腾讯云直播配置");
        //腾讯云im
        $this->addOption("app.tengxunyun.im.sdkAppid", '', "腾讯云IM SdkAppid", 1, 1, "腾讯云IM配置");
        $this->addOption("app.tengxunyun.im.key", '', "腾讯云IM密钥", 1, 1, "腾讯云IM配置");
        $this->addOption("app.tengxunyun.im.identifier", '', "腾讯云IM账号管理员，默认’administrator‘", 1, 1, "腾讯云IM配置");

        //阿里云点播
        $this->addOption("app.aliyun.vod.kms.keyId", '', "阿里云kms vod 加密主密钥，可以用别名", 1, 1, "阿里云点播配置");
        $this->addOption("app.aliyun.vod.templateGroupId", '', "阿里云点播转码模板组ID", 1, 1, "阿里云点播配置");
        $this->addOption("app.aliyun.vod.callbackSignPrivateKey", '', "阿里云点播回调鉴权私有key", 1, 1, "阿里云点播配置");
        //阿里云直播
        $this->addOption("app.aliyun.live.pushDomain", '', "阿里云直播推流域名", 1, 1, "阿里云直播配置");
        $this->addOption("app.aliyun.live.playDomain", '', "阿里云直播播流域名", 1, 1, "阿里云直播配置");
        $this->addOption("app.aliyun.live.pushDomainKey", '', "阿里云直播推流签名KEy", 1, 1, "阿里云直播配置");
        $this->addOption("app.aliyun.live.playDomainKey", '', "阿里云直播播放签名KEy", 1, 1, "阿里云直播配置");
        $this->addOption("app.aliyun.live.transcodeTemplateId", '{"FD":"lld","LD":"lsd","SD":"lhd","HD":"lud"}', "阿里云直播转码模板id OD:原画,FD:流畅,LD:标清,SD:高清,HD:超清,2K:2K,4K:4K", 1, 1, "阿里云直播配置");
        //阿里云短信
        $this->addOption("app.aliyun.smsSign", '学多多', "阿里云 短信签名名称 ", 1, 1,"阿里云短信配置");
        $this->addOption("app.aliyun.sms.captcha.code", '', "阿里云 验证码短信模板Code ", 1, 1,"阿里云短信配置");
        //阿里云oss
        $this->addOption("app.aliyun.oss.bucket", '', "阿里云oss Bucket ", 1, 1,"阿里云OSS存储配置");
        $this->addOption("app.aliyun.oss.endpoint", '', "阿里云oss endpoint ", 1, 1,"阿里云OSS存储配置");

        //微信小程序
        $this->addOption("app.wxmini.appid", '', "微信小程序app id", 1, 1, "微信小程序配置");
        $this->addOption("app.wxmini.secret", '', "微信小程序secret key", 1, 1, "微信小程序配置");
        //支付宝
        $this->addOption("app.alipay.appid", '', "支付宝app id", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.isSandbox", '0', "是否支付宝沙箱模式, 1-是，0-否", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.notifyUrl", 'http://dev.eduxplus.com/pay/alipayCallback', "授权回调地址", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.merchantPrivateKey", '', "应用私钥", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.encryptKey", '', "接口内容加密方式AES密钥", 1, 1, "支付宝支付配置");
        $this->addOption("app.alipay.alipayPublicKey", '', "支付宝公钥字符串，如果采用非证书模式，则无需赋值三个证书路径，改为赋值如下的支付宝公钥字符串即可", 1, 1, "支付宝支付配置");
        //微信支付
        $this->addOption("app.wxpay.appid", '', "微信支付app id", 1, 1, "微信支付配置");
        $this->addOption("app.wxpay.mchid", '', "微信支付商户 id", 1, 1, "微信支付配置");
        $this->addOption("app.wxpay.key", '', "微信支付api 密钥", 1, 1, "微信支付配置");
        $this->addOption("app.wxpay.notifyrl", 'http://dev.eduxplus.com/pay/wxpayCallback', "微信支付默认的订单回调地址", 1, 1, "微信支付配置");
        $this->addOption("app.wxpay.sandbox", '', "是否沙盒模式,0-否，1-是", 1, 1, "微信支付配置");

        //elasticsearch搜索配置
        $this->addOption("app.es.host", '', "elasticsearch 服务器host配置，例如https://username:password!#$?*abc@foo.com:9200/", 1, 1, "elasticsearch搜索配置");
        $this->addOption("app.es.index.pre", 'eduxplus_', "elasticsearch 索引前缀", 1, 1, "elasticsearch搜索配置");
        $this->addOption("app.es.index.shardsNumber", '1', "elasticsearch 索引分片数目", 1, 1, "elasticsearch搜索配置");
        $this->addOption("app.es.index.replicasNumber", '0', "elasticsearch 索引副本数目", 1, 1, "elasticsearch搜索配置");
        //消息
        $this->addOption("app.msgtpl.welcome", '注册成功！欢迎注册本站，有问题请先查看<a href="{href}">帮助中心</a>', "注册欢迎消息模板", 1, 1, "消息模板配置");

        //初始化用户
        $userModel = new BaseUser();
        $uuid = $this->helperService->getUuid();
        $pwd = $this->passwordEncoder->needsRehash($userModel, $this->adminUserPwd);
        $userModel->setSex(1);
        $userModel->setBirthday('1949-10-01');
        $userModel->setRegSource("pc");
        $userModel->setMobile($this->adminUserMobile);
        $mobileMask =  $this->mobileMaskService->encrypt($this->adminUserMobile);
        $userModel->setMobileMask($mobileMask);
        $userModel->setReportUid(0);
        $userModel->setUuid($uuid);
        $userModel->setDisplayName("超级管理员");
        $userModel->setFullName("超级管理员");
        $userModel->setIsAdmin(1);
        $userModel->setPassword($pwd);
        $userModel->setRealRole(1);
        $userModel->setGravatar("/assets/images/gravatar.jpeg");
        $userModel->setAppToken("111111");
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
        $this->addMenu("首页", "首页", 0, "admin_index", "fas fa-home", 0, $roleId, 1, 1, 1, 1);
        $this->addMenu("首页", "后台首页", 0, "admin_dashboard", "fas fa-home", 0, $roleId, 1, 0, 1, 1);
        $this->addMenu("文件上传", "文件上传处理", 0, "admin_glob_upload", "fas fa-upload", 1, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索用户名", "搜索用户名", 0, "admin_api_glob_searchUserDo", "", 2, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索管理员", "搜索管理员", 0, "admin_api_glob_searchAdminUserDo", "", 3, $roleId, 1, 1, 0, 1);

        $this->addMenu("搜索产品", "搜索产品", 0, "admin_api_glob_searchProductDo", "", 4, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索商品", "搜索商品", 0, "admin_api_glob_searchGoodsDo", "", 5, $roleId, 1, 1, 0, 1);
        $this->addMenu("搜索课程", "搜索课程", 0, "admin_api_glob_searchCourseDo", "", 6, $roleId, 1, 1, 0, 1);

        $this->addMenu("获取阿里云点播视频上传地址和凭证", "获取阿里云点播视频上传地址和凭证", 0, "admin_api_glob_aliyunVodCreateUploadVideoDo", "", 6, $roleId, 1, 1, 0, 1);
        $this->addMenu("阿里云刷新视频上传凭证", "阿里云刷新视频上传凭证", 0, "admin_api_glob_aliyunVodRefreshUploadVideoDo", "", 7, $roleId, 1, 1, 0, 1);
        $this->addMenu("阿里云点播播放信息", "阿里云点播播放信息", 0, "admin_api_glob_getAliyunVodPlayInfoDo", "", 8, $roleId, 1, 1, 0, 1);

        $this->addMenu("获取腾讯云点播视频上传凭证", "获取腾讯云点播视频上传凭证", 0, "admin_api_glob_tengxunyunSignatureDo", "", 9, $roleId, 1, 1, 0, 1);
        $this->addMenu("获取腾讯云点播播放网址加密", "获取腾讯云点播播放网址加密", 0, "admin_api_glob_tengxunyunVodEncryptionPlayUrlDo", "", 10, $roleId, 1, 1, 0, 1);
        $this->addMenu("获取腾讯云点播超级播放器签名", "获取腾讯云点播超级播放器签名", 0, "admin_api_glob_tengxunyunVodAndvancePlaySignDo", "", 11, $roleId, 1, 1, 0, 1);
        //安全模块
        $accMenuId = $this->addMenu("安全", "安全方面的管理", 0, "", "fas fa-key", 2, $roleId, 1, 0, 1);
        //菜单
        $menuMgId = $this->addMenu("菜单管理", "管理菜单以及对应页面的权限", $accMenuId, "admin_menu_index", "", 3, $roleId, 1, 0, 1);
        $this->addMenu("添加菜单页面", "菜单新增页面", $menuMgId, "admin_menu_add", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加菜单", "菜单新增处理", $menuMgId, "admin_api_menu_add", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("查看菜单页面", "菜单展示页面", $menuMgId, "admin_menu_view", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("编辑菜单页面", "菜单编辑展示页面", $menuMgId, "admin_menu_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑菜单", "菜单编辑处理", $menuMgId, "admin_api_menu_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除菜单", "删除菜单", $menuMgId, "admin_api_menu_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("更新菜单排序", "更新菜单排序", $menuMgId, "admin_api_menu_updateSort", "", 6, $roleId, 1, 1, 0);
        //角色
        $roleMgId = $this->addMenu("角色管理", "管理角色", $accMenuId, "admin_role_index", "", 1, $roleId, 1, 0, 1);
        $this->addMenu("添加角色页面", "显示添加角色页面", $roleMgId, "admin_role_add", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加角色", "添加角色处理", $roleMgId, "admin_api_role_add", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("编辑角色页面", "显示编辑角色页面", $roleMgId, "admin_role_edit", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("编辑角色", "编辑角色处理", $roleMgId, "admin_api_role_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("删除角色", "删除角色处理", $roleMgId, "admin_api_role_delete", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("批量删除角色", "批量删除角色处理", $roleMgId, "admin_api_role_batchdelete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("角色绑定菜单页面", "显示角色绑定菜单页面", $roleMgId, "admin_role_bindmenu", "", 6, $roleId, 1, 1, 0);
        $this->addMenu("角色绑定菜单", "角色绑定菜单处理", $roleMgId, "admin_api_role_bindmenu", "", 7, $roleId, 1, 1, 0);
        //用户
        $userMgId = $this->addMenu("用户管理", "管理用户", $accMenuId, "admin_user_index", "", 2, $roleId, 1, 0, 1);
        $this->addMenu("添加页面", "显示添加用户页面", $userMgId, "admin_user_add", "", 0, $roleId, 1, 1, 0);
        $this->addMenu("添加用户", "添加用户处理", $userMgId, "admin_api_user_add", "", 1, $roleId, 1, 1, 0);
        $this->addMenu("查看用户页面", "显示用户页面", $userMgId, "admin_user_view", "", 2, $roleId, 1, 1, 0);
        $this->addMenu("编辑用户页面", "显示编辑用户页面", $userMgId, "admin_user_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑用户", "编辑用户处理", $userMgId, "admin_api_user_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除用户", "删除用户处理", $userMgId, "admin_api_user_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("批量删除用户", "批量删除用户处理", $userMgId, "admin_api_user_bathdelete", "", 6, $roleId, 1, 1, 0);
        $this->addMenu("锁定/解锁用户", "锁定/解锁用户", $userMgId, "admin_api_user_switchLock", "", 7, $roleId, 1, 1, 0);
        //操作日志
        $adminlogMgId = $this->addMenu("操作日志", "操作日志", $accMenuId, "admin_adminlog_index", "", 3, $roleId, 1, 0, 1);
        //系统模块
        $sysMenuId = $this->addMenu("系统", "系统方面的管理", 0, "", "fa fa-gears", 3, $roleId, 1, 0, 1);
        $optionMgId = $this->addMenu("配置", "对系统的相关配置", $sysMenuId, "admin_option_index", "", 0, $roleId, 1, 0, 1);
        $this->addMenu("添加页面", "添加配置页面展示", $optionMgId, "admin_option_add", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("添加", "添加配置处理", $optionMgId, "admin_api_option_add", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("编辑页面", "编辑配置页面展示", $optionMgId, "admin_option_edit", "", 3, $roleId, 1, 1, 0);
        $this->addMenu("编辑", "编辑配置处理", $optionMgId, "admin_api_option_edit", "", 4, $roleId, 1, 1, 0);
        $this->addMenu("删除", "删除配置处理", $optionMgId, "admin_api_option_delete", "", 5, $roleId, 1, 1, 0);
        $this->addMenu("批量删除", "批量删除配置处理", $optionMgId, "admin_api_option_bathdelete", "", 6, $roleId, 1, 1, 0);
        //教研
        $teachMenuId = $this->addMenu("教研", "教学产品方面的管理", 0, "", "fa fa-bank", 4, $roleId, 0, 0, 1);
        //协议
        $agreementMgId = $this->addMenu("协议管理", "针对各种协议的管理", $teachMenuId, "admin_teach_agreement_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("查看", "查看", $agreementMgId, "admin_teach_agreement_view", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加页面", "添加页面展示", $agreementMgId, "admin_teach_agreement_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $agreementMgId, "admin_api_teach_agreement_add", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $agreementMgId, "admin_teach_agreement_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $agreementMgId, "admin_api_teach_agreement_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $agreementMgId, "admin_api_teach_agreement_delete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $agreementMgId, "admin_api_teach_agreement_bathdelete", "", 7, $roleId, 0, 1, 0);
        //分类
        $mgId = $this->addMenu("分类管理", "分类的管理", $teachMenuId, "admin_teach_category_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_teach_category_add", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_teach_category_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_teach_category_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_teach_category_delete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("更新排序", "更新排序", $mgId, "admin_api_teach_category_updateSort", "", 7, $roleId, 0, 1, 0);
        //课程管理
        $mgId = $this->addMenu("课程管理", "课程的管理", $teachMenuId, "admin_teach_course_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("添加页面", "添加页面展示", $mgId, "admin_teach_course_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_teach_course_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_teach_course_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_teach_course_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_teach_course_delete", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_teach_course_bathdelete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("课程上下架", "课程上下架", $mgId, "admin_api_teach_course_switchStatus", "", 6, $roleId, 0, 1, 0);
        //章节管理
        $mgId = $this->addMenu("课程章节管理", "课程章节管理", $mgId, "admin_teach_chapter_index", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("章节添加页面", "章节添加页面展示", $mgId, "admin_teach_chapter_add", "", 8, $roleId, 0, 1, 0);
        $this->addMenu("章节添加", "添加处理", $mgId, "admin_api_teach_chapter_add", "", 9, $roleId, 0, 1, 0);
        $this->addMenu("章节编辑页面", "编辑页面展示", $mgId, "admin_teach_chapter_edit", "", 10, $roleId, 0, 1, 0);
        $this->addMenu("章节编辑", "编辑处理", $mgId, "admin_api_teach_chapter_edit", "", 11, $roleId, 0, 1, 0);
        $this->addMenu("章节删除", "删除处理", $mgId, "admin_api_teach_chapter_delete", "", 12, $roleId, 0, 1, 0);
        $this->addMenu("章节更新排序", "章节更新排序", $mgId, "admin_api_teach_chapter_updateSort", "", 13, $roleId, 0, 1, 0);
        $this->addMenu("点播", "管理点播", $mgId, "admin_teach_chapter_vod", "", 14, $roleId, 0, 1, 0);
        $this->addMenu("点播管理处理", "点播添加、编辑等处理", $mgId, "admin_api_teach_chapter_vod", "", 14, $roleId, 0, 1, 0);
        $this->addMenu("附件管理", "附件添加、编辑", $mgId, "admin_teach_chapter_materials", "", 15, $roleId, 0, 1, 0);
        $this->addMenu("附件管理处理", "附件添加、编辑等处理", $mgId, "admin_api_teach_chapter_materials", "", 16, $roleId, 0, 1, 0);
        $this->addMenu("直播", "管理直播", $mgId, "admin_teach_chapter_live", "", 17, $roleId, 0, 1, 0);
        $this->addMenu("直播管理处理", "直播添加、编辑等处理", $mgId, "admin_api_teach_chapter_live", "", 18, $roleId, 0, 1, 0);

        //产品管理
        $mgId = $this->addMenu("产品管理", "产品的管理", $teachMenuId, "admin_teach_product_index", "", 0, $roleId, 0, 0, 1);
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
        $jwMenuId = $this->addMenu("教务", "教务方面的管理", 0, "", "fa fa-envira", 5, $roleId, 0, 0, 1);
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
        $mallMenuId = $this->addMenu("商城", "商城方面的管理", 0, "", "fa fa-shopping-cart", 6, $roleId, 0, 0, 1);
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
        //单页管理
        $mgId = $this->addMenu("单页管理", "单页信息管理", $mallMenuId, "admin_mall_page_index", "", 4, $roleId, 0, 0, 1);
        $this->addMenu("查看", "查看", $mgId, "admin_mall_page_view", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("添加页面", "添加页面展示", $mgId, "admin_mall_page_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加", "添加处理", $mgId, "admin_api_mall_page_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面", "编辑页面展示", $mgId, "admin_mall_page_edit", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑", "编辑处理", $mgId, "admin_api_mall_page_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("删除", "删除处理", $mgId, "admin_api_mall_page_delete", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_mall_page_bathdelete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("单页上下架", "单页上下架", $mgId, "admin_api_mall_page_switchStatus", "", 7, $roleId, 0, 1, 0);
        //帮助中心
        $helpmgId = $this->addMenu("帮助管理", "帮助管理", $mallMenuId, "", "", 5, $roleId, 0, 0, 0);
        $mgId = $this->addMenu("帮助列表", "帮助列表", $helpmgId, "admin_mall_help_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("添加", "添加展示", $mgId, "admin_mall_help_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_mall_help_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("查看", "查看", $mgId, "admin_mall_help_view", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面展示", "编辑展示", $mgId, "admin_mall_help_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_mall_help_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("删除", "单个删除处理", $mgId, "admin_api_mall_help_delete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_mall_help_bathdelete", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("帮助上下架", "帮助上下架处理", $mgId, "admin_api_mall_help_switchStatus", "", 8, $roleId, 0, 1, 0);
        //admin_api_mall_help_switchStatus
        //帮助分类
        $mgId = $this->addMenu("帮助分类", "帮助分类", $helpmgId, "admin_mall_help_category_index", "", 8, $roleId, 0, 0, 1);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_mall_help_category_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面展示", "编辑页面展示", $mgId, "admin_mall_help_category_edit", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_mall_help_category_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("删除", "单个删除处理", $mgId, "admin_api_mall_help_category_delete", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("更新排序", "更新排序处理", $mgId, "admin_api_mall_help_category_updateSort", "", 4, $roleId, 0, 1, 0);

        //资讯管理
        $newsmgId = $this->addMenu("资讯管理", "资讯管理", $mallMenuId, "", "", 5, $roleId, 0, 0, 1);
        $mgId = $this->addMenu("资讯列表", "资讯列表", $newsmgId, "admin_mall_news_index", "", 0, $roleId, 0, 0, 1);
        $this->addMenu("添加", "添加展示", $mgId, "admin_mall_news_add", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_mall_news_add", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("查看", "查看", $mgId, "admin_mall_news_view", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面展示", "编辑展示", $mgId, "admin_mall_news_edit", "", 4, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_mall_news_edit", "", 5, $roleId, 0, 1, 0);
        $this->addMenu("删除", "单个删除处理", $mgId, "admin_api_mall_news_delete", "", 6, $roleId, 0, 1, 0);
        $this->addMenu("批量删除", "批量删除处理", $mgId, "admin_api_mall_news_bathdelete", "", 7, $roleId, 0, 1, 0);
        $this->addMenu("资讯上下架", "资讯上下架处理", $mgId, "admin_api_mall_news_switchStatus", "", 8, $roleId, 0, 1, 0);
        //资讯分类
        $mgId = $this->addMenu("资讯分类", "资讯分类", $newsmgId, "admin_mall_news_category_index", "", 8, $roleId, 0, 0, 1);
        $this->addMenu("添加处理", "添加处理", $mgId, "admin_api_mall_news_category_add", "", 0, $roleId, 0, 1, 0);
        $this->addMenu("编辑页面展示", "编辑页面展示", $mgId, "admin_mall_news_category_edit", "", 1, $roleId, 0, 1, 0);
        $this->addMenu("编辑处理", "编辑处理", $mgId, "admin_api_mall_news_category_edit", "", 2, $roleId, 0, 1, 0);
        $this->addMenu("删除", "单个删除处理", $mgId, "admin_api_mall_news_category_delete", "", 3, $roleId, 0, 1, 0);
        $this->addMenu("更新排序", "更新排序处理", $mgId, "admin_api_mall_news_category_updateSort", "", 4, $roleId, 0, 1, 0);
        //banner管理
        $mgId = $this->addMenu("banner管理", "banner管理", $mallMenuId, "admin_mall_banner_index", "", 9, $roleId, 0, 0, 1);
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
