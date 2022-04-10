<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/1 13:46
 */

namespace Eduxplus\WebsiteBundle\Service;


use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Eduxplus\CoreBundle\Entity\BaseLoginLog;
use Eduxplus\CoreBundle\Entity\BaseUser;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Eduxplus\CoreBundle\Lib\Base\AppBaseService;
use Eduxplus\CoreBundle\Lib\Service\Base\MobileMaskService;
use Eduxplus\CoreBundle\Service\UserService as CoreUserService;

class UserService extends AppBaseService
{
    const LOGIN_KEY = "_security_app";
    const LOGIN_TOKEN = "LOGIN_TOKEN";
    protected $globService;
    protected $helperService;
    protected $tokenStorage;
    protected $eventDispatcher;
    protected $mobileMaskService;
    protected $coreUserServie;

    public function __construct(
        GlobService $globService,
        HelperService $helperService,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        MobileMaskService $mobileMaskService,
        CoreUserService $coreUserServie
    ) {
        $this->globService = $globService;
        $this->helperService = $helperService;
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->mobileMaskService = $mobileMaskService;
        $this->coreUserServie = $coreUserServie;
    }


    /**
     * 检查登录
     *
     * @param $mobile
     * @param string $source  app-ios,android, html-h5,pc, mini-小程序
     * @return bool
     */
    public function checkLogin($mobile, $source = 'html', $regSource = "pc")
    {
        //"sitelogin"
        //查询用户信息
        $mobileMask =  $this->mobileMaskService->encrypt($mobile);
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.mobileMask = :mobileMask";
        $userInfo = $this->db()->fetchOneHard($sql, ["mobileMask" => $mobileMask]);
        if (!$userInfo) {
            //没有用户信息则注册
            $displayName = $this->helperService->formatMobile($mobile);
            $model = new BaseUser();
            $model->setMobile($mobile);
            $model->setMobileMask($mobileMask);
            $model->setIsAdmin(0);
            $model->setIsLock(0);
            $model->setRegSource($regSource);
            $model->setSex(1);
            $model->setDisplayName($displayName);
            $model->setFullName($displayName);
            $model->setGravatar(trim($this->getOption("app.domain"),"/")."/bundles/eduxpluscore/images/gravatar.jpeg");
            $model->setMobileTail($mobile);
            $model->setSno($this->coreUserServie->getSno($mobile));
            $model->setBirthday(date('Y-m-d'));
            $model->setReportUid(0);
            $uid =  $this->db()->save($model);
        } else {
            $isLock = $userInfo["isLock"];
            if ($isLock) return $this->error()->add("账号已被锁定!");
            $deleteAt = $userInfo["deletedAt"];
            if ($deleteAt) return $this->error()->add("账号已被删除，请与管理员联系!");
            $uid = $userInfo["id"];
        }

        $token = $this->setLogin($uid, $source);
        return [$token, $uid];
    }

    public function checkSmsCaptcha($code, $mobile, $type)
    {
        return $this->globService->checkSmsCaptcha($code, $mobile, $type);
    }

    /**
     * @param $uid
     * @return BaseUser
     */
    public function getUserObj($uid)
    {
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id = :id";
        $user = $this->db()->fetchOne($sql, ["id" => $uid], 1);
        return $user;
    }

    public function getUserById($uid)
    {
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id = :id";
        $model = $this->db()->fetchOne($sql, ["id" => $uid]);
        if ($model) {
            $model["mobileView"] = $this->helperService->formatMobile($model["mobile"]);
        }
        return $model;
    }

    /**
     * 添加用户
     *
     * @param [type] $mobile
     * @param [type] $fullName
     * @param [type] $displayName
     * @param [type] $sex
     * @param string $source
     * @return void
     */
    public function add($mobile, $fullName, $displayName, $sex, $gravatar, $source = "mini")
    {
        $helperService = new HelperService();
        $uuid = $helperService->getUuid();
        $model = new BaseUser();
        $model->setMobile($mobile);
        $mobileMask =  $this->mobileMaskService->encrypt($mobile);
        $model->setMobileMask($mobileMask);
        $model->setGravatar($gravatar);
        $model->setFullName($fullName);
        $model->setDisplayName($displayName);
        $model->setSex($sex);
        $model->setRegSource($source);
        $uid = $this->db()->save($model);

        return $uid;
    }


    /**
     * 设置登录token
     *
     * @param $uid
     * @param string $source
     */
    public function setLogin($uid, $source = 'html')
    {
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id = :id";
        $model = $this->db()->fetchOneHard($sql, ["id" => $uid], 1);

        $token = session_create_id("");
        if ($source == "html") {
            $model->setHtmlToken($token);
        } else if ($source == "app") {
            $model->setAppToken($token);
        } else if ($source == "wxmini") {
            $model->setWxminiToken($token);
        }

        $this->db()->update($model);

        //登录记录
        $loginLog = new BaseLoginLog();
        $loginLog->setUid($uid);
        $loginLog->setSource($source);
        $ip = $this->request()->getClientIp();
        $loginLog->setIp($ip);
        $this->db()->save($loginLog);
        //set token
        return $token;
    }


    public function edit($uid, $displayName, $fullName, $birthday, $sex, $avatar)
    {
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id = :id";
        $user = $this->db()->fetchOne($sql, ["id" => $uid], 1);
        if (!$user) return false;
        $user->setDisplayName($displayName);
        $user->setFullName($fullName);
        $user->setBirthday($birthday);
        $user->setSex($sex);
        $user->setGravatar($avatar);
        $this->db()->save($user);
        return true;
    }

    public function checkMobileExist($mobile)
    {
        $mobileMask =  $this->mobileMaskService->encrypt($mobile);
        $sql = "SELECT a FROM Core:BaseUser a WHERE  a.mobileMask=:mobileMask";
        $user = $this->db()->fetchOne($sql, ["mobileMask" => $mobileMask]);
        return $user;
    }

    public function updateMobile($uid, $mobile)
    {
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id = :id";
        $user = $this->db()->fetchOne($sql, ["id" => $uid], 1);
        $user->setMobile($mobile);
        $mobileMask =  $this->mobileMaskService->encrypt($mobile);
        $user->setMobileMask($mobileMask);
        $this->db()->save($user);
        return true;
    }
}
