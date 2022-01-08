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
use Doctrine\Persistence\ObjectManager;
use Eduxplus\CoreBundle\Lib\Service\Base\MobileMaskService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Eduxplus\CoreBundle\DataFixtures\Fixtures\MenuFixtures;
use Eduxplus\CoreBundle\DataFixtures\Fixtures\OptionsFixtures;

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
    protected $menuFixtures;
    protected $optionsFixtures;

    public function __construct(
        UserPasswordHasherInterface $passwordEncoder,
        HelperService $helperService,
        MobileMaskService $mobileMaskService,
        MenuFixtures $menuFixtures,
        OptionsFixtures $optionsFixtures,
        string $adminUserMobile,
        string $adminUserPwd
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->helperService = $helperService;
        $this->mobileMaskService = $mobileMaskService;
        $this->adminUserMobile = $adminUserMobile;
        $this->adminUserPwd = $adminUserPwd;
        $this->optionsFixtures =$optionsFixtures;
        $this->menuFixtures =$menuFixtures;
    }


    public function load(ObjectManager $manager)
    {
         //初始化用户
         $userModel = new BaseUser();
         $pwd = $this->passwordEncoder->hashPassword($userModel, $this->adminUserPwd);
         $userModel->setSex(1);
         $userModel->setBirthday('1949-10-01');
         $userModel->setRegSource("pc");
         $userModel->setMobile($this->adminUserMobile);
         $mobileMask =  $this->mobileMaskService->encrypt($this->adminUserMobile);
         $userModel->setMobileMask($mobileMask);
         $userModel->setReportUid(0);
         $userModel->setSno(substr($this->adminUserMobile,-6));
         $userModel->setMobileTail($this->adminUserMobile);
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
         $roleModel->setId(1);
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

         $this->optionsFixtures->load();
         $this->menuFixtures->load($roleId);
    }

}
