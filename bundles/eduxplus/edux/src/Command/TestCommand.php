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
        //帮助中心
        $roleId = 1;
        $mallMenuId = 108;
        $jwMenuId = 94;
        $menuId = 213;
        $sysMenuId = 352050054531383296;
        $this->addOptionConfig("app.geoip2City.path", '1', "ip定位城市数据文件服务器本地地址", 1, 1, "网站基本配置");
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
