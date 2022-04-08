<?php

namespace Eduxplus\EduxBundle\Command;

use Eduxplus\CoreBundle\Lib\Service\Base\EsService;
use Eduxplus\CoreBundle\Lib\Service\Base\File\AliyunOssService;
use Eduxplus\CoreBundle\Lib\Service\Base\Pay\AlipayService;
use Eduxplus\CoreBundle\Lib\Service\Base\Pay\WxpayService;
use Eduxplus\CoreBundle\Lib\Service\Base\SmsService;
use Eduxplus\CoreBundle\Lib\Service\Base\UploadService;
use Eduxplus\CoreBundle\Lib\Service\Base\Vod\TengxunyunVodService;
use Eduxplus\CoreBundle\Entity\BaseMenu;
use Eduxplus\CoreBundle\Entity\BaseRoleMenu;
use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Doctrine\Persistence\ObjectManager;
use EasyWeChat\Kernel\Support\XML;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\EduxBundle\Service\Mall\OrderService;
use Eduxplus\EduxBundle\Service\Mall\PayService;
use Eduxplus\EduxBundle\Service\Mall\CouponService;
use Eduxplus\CoreBundle\Entity\BaseRole;
use Eduxplus\CoreBundle\Entity\BaseRoleUser;
use Eduxplus\CoreBundle\Lib\Service\Base\MobileMaskService;
use Eduxplus\CoreBundle\Entity\BaseUser;
use Eduxplus\CoreBundle\Entity\BaseOption;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'edux:test';
    protected $aliyunOssService;
    protected $uploadService;
    protected $manager;
    protected $tengxunyunVodService;
    protected $smsService;
    protected $alipayService;
    protected $helperService;
    protected $esService;
    protected $passwordEncoder;
    protected $mobileMaskService;
    /**
     * TestCommand constructor.
     */
    public function __construct(
        UserPasswordHasherInterface $passwordEncoder,
        AliyunOssService $aliyunOssService,
        UploadService $uploadService,
        CouponService $couponService,
        OrderService $orderService,
        HelperService $helperService,
        PayService $payService,
        TengxunyunVodService $tengxunyunVodService,
        SmsService $smsService,
        AlipayService $alipayService,
        EsService $esService,
        MobileMaskService $mobileMaskService,
        WxpayService $wxpayService
    ) {
        $this->aliyunOssService = $aliyunOssService;
        $this->uploadService = $uploadService;
        $this->couponService = $couponService;
        $this->orderService = $orderService;
        $this->payService = $payService;
        $this->tengxunyunVodService = $tengxunyunVodService;
        $this->uploadService = $uploadService;
        $this->smsService = $smsService;
        $this->alipayService = $alipayService;
        $this->esService = $esService;
        $this->wxpayService = $wxpayService;
        $this->helperService = $helperService;
        $this->passwordEncoder = $passwordEncoder;
        $this->mobileMaskService = $mobileMaskService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('test');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->manager();

//        $userModel = new BaseUser();
//        $uuid = $this->helperService->getUuid();
//        $pwd = $this->passwordEncoder->needsRehash($userModel, "111111");
//        $userModel->setSex(1);
//        $userModel->setBirthday('1988-10-01');
//        $userModel->setRegSource("pc");
//        $userModel->setMobile("17621487000");
//        $mobileMask =  $this->mobileMaskService->encrypt("17621487000");
//        $userModel->setMobileMask($mobileMask);
//        $userModel->setReportUid(0);
//        $userModel->setUuid($uuid);
//        $userModel->setDisplayName("超级管理员");
//        $userModel->setFullName("管理员大大");
//        $userModel->setIsAdmin(1);
//        $userModel->setPassword($pwd);
//        $userModel->setRealRole(1);
//        $userModel->setGravatar("http://demo.eduxplus.com/assets/images/gravatar.jpeg");
//        $userModel->setAppToken("sdasdasda");
//        $this->manager->persist($userModel);
//        $this->manager->flush();
//        $uid = $userModel->getId();
//
//        //绑定用户角色
//        $roleUserModel = new BaseRoleUser();
//        $roleUserModel->setUid($uid);
//        $roleUserModel->setRoleId(1);
//        $this->manager->persist($roleUserModel);
//        $this->manager->flush();

        //帮助中心
        $roleId = 1;
        $mallMenuId = 108;
        $jwMenuId = 94;
        $menuId = 213;
        $sysMenuId = 352050054346833920;
        $this->addMenu("关于", "关于", $sysMenuId, "admin_about", "mdi  mdi-information-variant", 1, $roleId, 1, 0, 1, 1);
//        $this->addMenu("首页", "首页(布局)", 0, "admin_index", "fas fa-home", 0, $roleId, 1, 0, 1, 1);
//
//        $this->addMenu("搜索试卷产品", "搜索试卷产品", 0, "admin_qa_api_glob_searchProductDo", "", 3, $roleId, 1, 1, 0, 1);
//        $this->addMenu("搜索试卷商品", "搜索试卷商品", 0, "admin_qa_api_glob_searchGoodsDo", "", 3, $roleId, 1, 1, 0, 1);

//        $mgId = $this->addMenu("试卷商品管理", "试卷商品管理", $menuId, "admin_qa_mall_goods_index", "", 2, $roleId, 0, 0, 1);
//        $this->addMenu("查看单个试卷商品信息", "查看单个试卷商品信息", $mgId, "admin_qa_mall_goods_view", "", 0, $roleId, 0, 1, 0);
//        $this->addMenu("查看组合试卷商品信息", "查看组合试卷商品信息", $mgId, "admin_qa_mall_goods_viewgroup", "", 1, $roleId, 0, 1, 0);
//        $this->addMenu("添加单个试卷商品页面", "添加单个试卷商品页面展示", $mgId, "admin_qa_mall_goods_add", "", 2, $roleId, 0, 1, 0);
//        $this->addMenu("添加组合试卷商品页面", "添加组合试卷商品页面展示", $mgId, "admin_qa_mall_group_goods_add", "", 3, $roleId, 0, 1, 0);
//        $this->addMenu("添加试卷商品处理", "添加试卷商品处理", $mgId, "admin_qa_api_mall_goods_add", "", 4, $roleId, 0, 1, 0);
//        $this->addMenu("编辑单个试卷商品页面", "编辑单个试卷商品页面展示", $mgId, "admin_qa_mall_goods_edit", "", 5, $roleId, 0, 1, 0);
//        $this->addMenu("编辑组合试卷商品页面", "编辑组合试卷商品页面展示", $mgId, "admin_qa_mall_goods_editgroup", "", 6, $roleId, 0, 1, 0);
//        $this->addMenu("编辑试卷商品处理", "编辑试卷商品处理", $mgId, "admin_qa_api_mall_goods_edit", "", 7, $roleId, 0, 1, 0);
//        $this->addMenu("删除试卷商品", "删除试卷商品处理", $mgId, "admin_qa_api_mall_goods_delete", "", 8, $roleId, 0, 1, 0);
//        $this->addMenu("批量删除试卷商品", "批量删除试卷商品处理", $mgId, "admin_qa_api_mall_goods_bathdelete", "", 9, $roleId, 0, 1, 0);
//        $this->addMenu("试卷商品上下架", "试卷商品上下架", $mgId, "admin_qa_api_mall_goods_switchStatus", "", 10, $roleId, 0, 1, 0);

//        $this->addMenu("单个banner列表", "单个banner列表", $mgId, "admin_mall_bannermain_index", "", 7, $roleId, 0, 1, 0);
//        $this->addOptionConfig("app.es.host", 'v2.eduxplus.com:9288', "elasticsearch 服务器host配置，例如https://username:password!#$?*abc@foo.com:9200/", 1, 1, "elasticsearch搜索配置");

//        $this->esService->esCreateIndex("goods", "name", 1);

//        $sql = "SELECT a FROM Edux:MallGoods a WHERE a.status=1";
//        $list = $this->esService->fetchAll($sql);
//        if($list){
//            foreach ($list as $v){
//                $this->esService->esUpdate("goods", $v['id'], ["name"=>$v['name']]);
//            }
//        }

//       $rs =  $this->esService->esSearch("jihe", "goods", "name", 0, 10);
//        var_dump($rs);
//        $this->esService->esCreateIndex("news", "title", 1);
//        $this->addOptionConfig("app.search.adapter", '2', "搜索方式，1-本地数据库，2-elasticsearch", 1, 1, "网站配置");

//        $mgId = $this->addMenu("班级管理", "班级管理", $jwMenuId, "admin_jw_class_index", "", 2, $roleId, 0, 0, 1);
//        $mgId = 208;
//        $this->addMenu("学员管理", "学员管理", $mgId, "admin_jw_class_members", "", 0, $roleId, 0, 1, 0);

//        $this->addMenu("搜索管理员", "搜索管理员", 0, "admin_api_glob_searchAdminUserDo", "", 3, $roleId, 1, 1, 0, 1);
//        $this->addOptionConfig("app.msgtpl.welcome", '注册成功！欢迎注册本站，有问题请先查看<a href="{href}">帮助中心</a>', "注册欢迎消息模板", 1, 1, "消息模板配置");
//        $this->msgService->send(0, "app.msgtpl.welcome", ["href"=>"http://dev.eduxplus.com/help"]);
//        $rs = $this->msgService->parseMsg("app.msgtpl.welcome", ["href"=>"http://dev.eduxplus.com/help"]);
//        var_dump($rs);
//        $sql = "SELECT * FROM mall_msg WHERE uid =?";
//        $rs = $this->msgService->fetchAssocBySql($sql, [0]);
//        var_dump($rs);
//        $sql = "SELECT * FROM Edux:MallMsg WHERE uid =?";
//        $rs =  $this->msgService->formatTableClass($sql, ["Edux:MallMsg"]);
//        var_dump($rs);
//        $sql = "SELECT a FROM Core:BaseLoginLog a WHERE a.id=1";
//        $model = $this->msgService->fetchOne($sql,[], 1);
//        $this->msgService->hardDelete($model);

//        $mgId = $this->addMenu("章节点管理", "管理试题章节点", 1, "qa_admin_chapter_index", "", 1, $roleId, 1, 0, 1);
//        $this->addMenu("章节点集合状态切换", "章节点集合状态切换", $mgId, "qa_admin_chapter_switchStatus", "", 0, $roleId, 1, 1, 0);
//        $this->addMenu("章节点集合添加", "展示章节点集合添加页面", $mgId, "qa_admin_chapter_add", "", 0, $roleId, 1, 1, 0);
//        $this->addMenu("章节点集合添加处理", "章节点集合添加处理", $mgId, "qa_admin_chapter_do_add", "", 1, $roleId, 1, 1, 0);
//        $this->addMenu("章节点集合编辑", "展示章节点集合编辑页面", $mgId, "qa_admin_chapter_edit", "", 2, $roleId, 1, 1, 0);
//        $this->addMenu("章节点集合编辑处理", "章节点集合编辑处理", $mgId, "qa_admin_chapter_do_edit", "", 3, $roleId, 1, 1, 0);
//        $this->addMenu("章节点集合单个删除", "章节点集合单个删除", $mgId, "qa_admin_chapter_delete", "", 4, $roleId, 1, 1, 0);
//        $this->addMenu("章节点集合批量删除", "章节点集合批量删除", $mgId, "qa_admin_chapter_bathdelete", "", 5, $roleId, 1, 1, 0);
//
//        $this->addMenu("试题添加", "试题添加", $mgId, "qa_admin_node_add", "", 14, $roleId, 0, 1, 0);
//        $this->addMenu("试题添加处理", "试题添加处理", $mgId, "qa_admin_node_do_add", "", 15, $roleId, 0, 1, 0);
//        $this->addMenu("试题编辑", "试题编辑", $mgId, "qa_admin_node_edit", "", 16, $roleId, 0, 1, 0);
//        $this->addMenu("试题编辑处理", "试题编辑处理", $mgId, "qa_admin_node_do_edit", "", 17, $roleId, 0, 1, 0);
//        $this->addMenu("试题删除", "试题删除", $mgId, "qa_admin_node_delete", "", 18, $roleId, 0, 1, 0);
//        $this->addMenu("试题批量删除", "试题批量删除", $mgId, "qa_admin_node_bathdelete", "", 19, $roleId, 0, 1, 0);
//        $this->addMenu("试题发布", "试题发布", $mgId, "qa_admin_node_switchStatus", "", 20, $roleId, 0, 1, 0);
//
//        $this->addMenu("试题查看", "试题查看", $mgId, "qa_admin_node_view", "", 14, $roleId, 0, 1, 0);
//        $menuId = 211;
//        $mgId = $this->addMenu("试卷管理", "试卷管理", $menuId, "qa_admin_test_index", "", 1, $roleId, 0, 0, 1);
//        $this->addMenu("试卷添加", "试卷添加", $mgId, "qa_admin_test_add", "", 0, $roleId, 0, 1, 0);
//        $this->addMenu("试卷添加处理", "试卷添加处理", $mgId, "qa_admin_test_do_add", "", 1, $roleId, 0, 1, 0);
//        $this->addMenu("试卷编辑", "试卷编辑", $mgId, "qa_admin_test_edit", "", 2, $roleId, 0, 1, 0);
//        $this->addMenu("试卷编辑处理", "试卷编辑处理", $mgId, "qa_admin_test_do_edit", "", 3, $roleId, 0, 1, 0);
//        $this->addMenu("试卷删除", "试卷删除", $mgId, "qa_admin_test_delete", "", 4, $roleId, 0, 1, 0);
//        $this->addMenu("试卷批量删除", "试卷批量删除", $mgId, "qa_admin_test_bathdelete", "", 5, $roleId, 0, 1, 0);
//        $this->addMenu("试卷发布", "试卷发布", $mgId, "qa_admin_test_switchStatus", "", 6, $roleId, 0, 1, 0);
//        $this->addMenu("试卷预览", "试卷预览", $mgId, "qa_admin_test_preview", "", 7, $roleId, 0, 1, 0);
//
//        $mgId = $this->addMenu("试题管理", "试题管理", $mgId, "qa_admin_test_sub_index", "", 8, $roleId, 0, 1, 0);
//        $this->addMenu("试题添加", "试题添加", $mgId, "qa_admin_test_sub_mg", "", 0, $roleId, 0, 1, 0);
//        $this->addMenu("试题添加处理", "试题添加处理", $mgId, "qa_admin_test_sub_do_mg", "", 1, $roleId, 0, 1, 0);
//        $this->addMenu("试题编辑", "试题编辑", $mgId, "qa_admin_test_sub_edit", "", 2, $roleId, 0, 1, 0);
//        $this->addMenu("试题编辑处理", "试题编辑处理", $mgId, "qa_admin_test_sub_do_edit", "", 3, $roleId, 0, 1, 0);
//        $this->addMenu("试题删除", "试题删除", $mgId, "qa_admin_test_sub_delete", "", 4, $roleId, 0, 1, 0);
//        $this->addMenu("试题批量删除", "试题批量删除", $mgId, "qa_admin_test_sub_bathdelete", "", 5, $roleId, 0, 1, 0);
//
//        $this->addMenu("搜索章节点集合", "搜索章节点集合", 0, "qa_admin_chapter_searchChapter", "", 3, $roleId, 1, 1, 0, 1);
//        $this->addMenu("获取章节点树节点", "获取章节点树节点", 0, "qa_admin_chaptersub_getChapterSub_do", "", 3, $roleId, 1, 1, 0, 1);
        //qa_admin_chaptersub_getChapterSub_do

//        $this->addOptionConfig("app.wxpay.appid", 'wxde0c292600278a0d', "微信支付app id", 1, 1, "微信支付配置");
//        $this->addOptionConfig("app.wxpay.mchid", '1605226225', "微信支付商户 id", 1, 1, "微信支付配置");
//        $this->addOptionConfig("app.wxpay.key", 'sads1312423WWWWsss4433eerrggffff', "微信支付api 密钥", 1, 1, "微信支付配置");
//        $this->addOptionConfig("app.wxpay.notifyrl", 'http://dev.eduxplus.com/pay/wxpayCallback', "微信支付默认的订单回调地址", 1, 1, "微信支付配置");
//        $this->addOptionConfig("app.wxpay.sandbox", 0, "是否沙盒模式,0-否，1-是", 1, 1, "微信支付配置");
//        $subject = "经济学基础";
//        $orderNo = "20210113o70627625b3ba8ddf";
//        $orderNo = "20210113o2feb472f0fd2a3f21";
//        $orderAmount = 10;

//        $siteDomain = $this->wxpayService->getOption("app.domain");
//        $returnUrl = trim($siteDomain, "/").$this->wxpayService->genUrl("app_glob_pay_wxpayCallback");
//
//        $this->wxpayService->pagePay($subject, $orderNo, $orderAmount, $returnUrl, $tradeType="NATIVE", $orderNo);
//        查询信息
//        $rs = $this->wxpayService->query($orderNo);
//        var_dump($rs);
//            $xml = '<xml><appid><![CDATA[wxde0c292600278a0d]]></appid>\n<bank_type><![CDATA[OTHERS]]></bank_type>\n<cash_fee><![CDATA[1]]></cash_fee>\n<fee_type><![CDATA[CNY]]></fee_type>\n<is_subscribe><![CDATA[Y]]></is_subscribe>\n<mch_id><![CDATA[1605226225]]></mch_id>\n<nonce_str><![CDATA[5ffe96c7ad3d8]]></nonce_str>\n<openid><![CDATA[oggHj6Hx-hfNBdPQdFM9TO6whVhk]]></openid>\n<out_trade_no><![CDATA[20210113o254d30458bb4aba27]]></out_trade_no>\n<result_code><![CDATA[SUCCESS]]></result_code>\n<return_code><![CDATA[SUCCESS]]></return_code>\n<sign><![CDATA[4746CFAE1BD3BB9A346FFB5947C1FE60]]></sign>\n<time_end><![CDATA[20210113144436]]></time_end>\n<total_fee>1</total_fee>\n<trade_type><![CDATA[NATIVE]]></trade_type>\n<transaction_id><![CDATA[4200000835202101133716694628]]></transaction_id>\n</xml>';
//            $arr = XML::parse($xml);
//        $arr=["return_code"=>"SUCCESS", "return_msg"=>"OK"];
//            $xm = XML::build($arr);
//            var_dump($xm);
        return 0;
    }

    protected function addOptionConfig($key, $value, $descr, $type = 1, $isLock = 1, $group='')
    {
        $kernel = $this->getApplication()->getKernel();
        $container = $kernel->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $optionModel = new BaseOption();
        $optionModel->setOptionKey($key);
        $optionModel->setOptionValue($value);
        $optionModel->setDescr($descr);
        $optionModel->setIsLock($isLock);
        $optionModel->setOptionGroup($group);
        $optionModel->setType($type);
        $entityManager->persist($optionModel);
        $entityManager->flush();
    }

    function manager(){
        $kernel = $this->getApplication()->getKernel();
        $container = $kernel->getContainer();
        $manager = $container->get('doctrine')->getManager();
        return $this->manager = $manager;
    }

    function addMenu($name, $descr, $pid, $uri, $style, $sort, $roleId, $isLock, $isAccess, $isShow, $isGlobal = 0)
    {
        $this->manager();
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
