<?php

namespace App\DataFixtures;

use App\Bundle\AppBundle\Lib\Service\HelperService;
use App\Entity\BaseMenu;
use App\Entity\BaseRole;
use App\Entity\BaseRoleMenu;
use App\Entity\BaseRoleUser;
use App\Entity\BaseUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
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
        //初始化用户
        $userModel = new BaseUser();
        $uuid = $this->helperService->getUuid();
        $pwd = $this->passwordEncoder->encodePassword($userModel, "111111");
        $userModel->setSex(1);
        $userModel->setBirthday('1988-10-01');
        $userModel->setRegSource("web");
        $userModel->setMobile("17621487072");
        $userModel->setReportUid(0);
        $userModel->setUuid($uuid);
        $userModel->setDisplayName("超级管理员");
        $userModel->setFullName("管理员大大");
        $userModel->setRoles(["ROLE_ADMIN"]);
        $userModel->setPassword($pwd);
        $userModel->setGravatar("/assets/images/defaultFace.jpeg");
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
        $accMenuId = $this->addMenu("安全","安全方面的管理", 0,"", "fas fa-key",0, $roleId, 1, 0, 1);
        $menuMgId = $this->addMenu("菜单管理","管理菜单以及对应页面的权限", $accMenuId,"admin_menu_index", "",0, $roleId, 1, 0, 1);
        $this->addMenu("添加菜单","新增菜单", $menuMgId,"admin_api_menu_add", "",0, $roleId, 1, 1, 0);

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
