<?php
/**
 * Created by PhpStorm.
 * User: wangkh
 * Date: 2018/9/20
 * Time: 17:47
 */

namespace Site\Service;


use Lib\Base\BaseService;
use Lib\Support\Error;
use Lib\Support\PasswordStorage;
use Lib\Support\Tool;
use Lib\Support\UUid;
use Lib\Support\Valid;
use Trensy\Http\Request;

final class AccountService extends BaseService
{
    const LOGIN_KEY = "SITE_LOGIN_KEY";
    const LOGIN_TOKEN = 'SITE_LOGIN_TOKEN';
    const MOBILE_STEP_TOKEN = 'MOBILE_STEP_TOKEN';

    /**
     * @var \Admin\Dao\System\UserDao
     */
    public $usersDao;

    /**
     * @var \Glob\Service\SmsService
     */
    public $smsService;


    /**
     * @param $guid
     * @param null $displayName
     * @param null $fullName
     * @param null $faceImg
     * @param null $birthday
     * @param null $sex
     * @return bool
     * @throws \Exception
     */
    public function editUser($uid, $displayName=null, $fullName=null, $faceImg=null,$birthday=null, $sex=null)
    {
        $check = $this->usersDao->getField("id", ['guid'=>$guid]);
        if(!$check) return Error::add("用户不存在!");
        $data = [];
        if($fullName !== null) $data['full_name'] = $fullName;
        if($displayName !== null) $data['display_name'] = $displayName;
        if($faceImg !== null) $data['face_img'] = $faceImg;
        if($birthday !== null) $data['birthday'] = $birthday;
        if($sex !== null) $data['sex'] = $sex;
        $do = $this->usersDao->autoUpdate($data, ['id'=>$uid]);
        return $do;
    }


    public function editPwd($mobile, $pwd)
    {
        $hashpwd = $this->createHash($pwd);
        $data = [];
        $data['passwd'] = $hashpwd;
        $do = $this->usersDao->autoUpdate($data, ['mobile'=>$mobile]);
        if($do){
            return true;
        }else{
            return false;
        }
    }

    public function regUser($mobile, $pwd, $source)
    {
        $check = $this->usersDao->get(['mobile'=>$mobile]);
        if (empty($pwd)) {
            $pwd = substr(UUid::getShortuuid(), 0, 20);
        }
        $randStr = time();
        $disPlayName =  '新用户'.$randStr;
        if($check) return Error::add('手机号码已注册');
        try{
            //同步
            $hashpwd = $this->createHash($pwd);
            $guid = UUid::getUuid();
            $data = [];
            $data['guid'] = $guid;
            $data['mobile'] = $mobile;
            $data['display_name'] = $disPlayName;
            $data['passwd'] = $hashpwd;
            $data['is_lock'] = 0;
            $data['reg_source'] = $source;
            $uid = $this->usersDao->autoAdd($data);
            return $uid;
        }catch (\Exception $e){
            return Error::add($e->getMessage());
        }
    }




    public function getUserByMobile($mobile)
    {
        return $this->usersDao->get(['mobile'=>$mobile]);
    }

    /**
     * 字符串hash
     * @param $str
     */
    public function createHash($str)
    {
        return PasswordStorage::create_hash($str);
    }

    public function verifyPassword($password, $hash)
    {
        return PasswordStorage::verify_password($password, $hash);
    }

    public function loginCheck($mobile, $pwd){
        $userInfo = $this->usersDao->get(['mobile'=>$mobile]);
        if(!$userInfo) return Error::add("手机号码未注册!");
        $pwdhash = $userInfo['passwd'];
        $pwdCheck = $this->verifyPassword($pwd, $pwdhash);
        if(!$pwdCheck)  return Error::add("密码错误!");
        return $userInfo;
    }

    /**
     * 短信检查
     *
     * @param $mobile
     * @param $code
     */
    public function smsCheck($mobile, $code, $type)
    {
        $check = $this->smsService->checkSmsCaptcha($code, $mobile, $type);
        if(!$check) return false;
        return true;
    }


    /**
     * 判断手机号码是否注册
     *
     * @param $mobile
     * @return bool
     */
    public function isMobileReg($mobile)
    {
        $userInfo = $this->usersDao->get(['mobile'=>$mobile]);
        if(!$userInfo) return false;
        return $userInfo;
    }

    /**
     *  同一端只能有一个登录(pc端用)
     *
     * @param $uid
     * @param string $source
     * @return bool
     */
    public function sourceTokenCheck($uid)
    {
        $token = $this->session()->getSid();
        $info = $this->usersDao->get(['id'=>$uid]);
        $oldToken = $info['pc_login_token'];
        return $token == $oldToken;
    }


    public function setLogin($uid, $source='pc'){
        //生成登录token
        if($source == 'pc'){
            $token = $this->session()->getSid();
        }else{
            $token = sha1(UUid::getShortuuid());
        }
        $update=[];
        if($source == 'pc'){
            $update['pc_login_token'] = $token;
            $key = self::LOGIN_KEY;
            $this->session()->set($key, $uid);
        }
        if($source == 'app'){
            $update['app_login_token'] = $token;
        }
        $update['last_login_time'] = time();

        $this->usersDao->autoUpdate($update, ['id'=>$uid]);

        return $token;
    }

    /**
     * 获取app 登陆信息
     *
     * @param $token
     * @return null
     */
    public function getAppLogin($token)
    {
        $user = $this->usersDao->get(['app_login_token'=>$token]);
        if($user){
            unset($user['passwd']);
            return $user;
        }
        return null;
    }

    /**
     * 是否登录
     * @return mixed
     */
    public function getLogin(){
        $userId = $this->session()->get(self::LOGIN_KEY);
        if (!$userId){
            //自动登录
            $userId = $this->rememberMe();
            if(!$userId) return [];
        }
        $userInfo = $this->usersDao->get(['id' => $userId]);
        unset($userInfo['passwd']);
        return $userInfo;
    }

    /**
     * 退出
     */
    public function logout()
    {
        $this->session()->del(self::LOGIN_KEY);
        $request= $this->getRequest();

        $sessionId = $request->cookies->get(self::LOGIN_TOKEN);
        if (!$sessionId) return;
        $cacheKey = self::LOGIN_TOKEN . "_" . $sessionId;
        $userId = $this->redis()->get($cacheKey);
        if (!$userId) return true;
        $this->destoryRememberMe();
        return true;
    }


    public function rememberMe($source='pc')
    {
        $request= $this->getRequest();
        $sessionId = $request->cookies->get(self::LOGIN_TOKEN);
        if (!$sessionId) return false;
        $cacheKey = self::LOGIN_TOKEN . "_" . $sessionId;
        $uid = $this->redis()->get($cacheKey);
        if (!$uid) return false;
        $this->setLogin($uid, $source);
        return $uid;
    }

    /**
     * 记住我
     *
     * @param Response $response
     */
    public function setRememberMe($uid)
    {
        $response = $this->getResponse();
        $sessionId = $this->session()->getSid();
        $key = self::LOGIN_TOKEN;
        //30天免登录
        $time = 60 * 60 * 24 * 30;
        $expire = time() + $time;
        $response->cookie($key, $sessionId, $expire);
        $cacheKey = $key . "_" . $sessionId;
        $this->redis()->setex($cacheKey, $time, $uid);
        return true;
    }

    public function destoryRememberMe()
    {
        $response = $this->getResponse();
        $request= $this->getRequest();
        $sessionId = $request->cookies->get(self::LOGIN_TOKEN);
        $response->cookie(self::LOGIN_TOKEN, 0, time() - 1);
        $cacheKey = self::LOGIN_TOKEN . "_" . $sessionId;
        $this->redis()->del($cacheKey);
    }

    public function setMobileStepToken($mobile, $type)
    {
        $key = self::MOBILE_STEP_TOKEN."_".$mobile."_".$type;
        $sec = 3600;//1小时
        $this->cache()->set($key, 1, $sec);
        return true;
    }

    public function checkMobileStepToken($mobile, $type)
    {
        $key = self::MOBILE_STEP_TOKEN."_".$mobile."_".$type;
        $result = $this->cache()->get($key);
        return $result;
    }

    public function clearMobileStepToken($mobile, $type)
    {
        $key = self::MOBILE_STEP_TOKEN."_".$mobile."_".$type;
        $this->cache()->del($key);
        return true;
    }

    /**
     * 登录次数检查
     *
     * @param $mobile
     * @return bool
     */
    public function loginErrorCheck($mobile)
    {
        $key = "LOGIN_ERROR_CHECK_".$mobile;
        $times = 3;//3次错误
        $vl = $this->cache()->get($key);
        if($vl >= $times){
            return false;
        }
        return true;
    }

    /**
     * 登录错误次数记录
     * @param $mobile
     */
    public function loginErrorLog($mobile)
    {
        $key = "LOGIN_ERROR_CHECK_".$mobile;
        $vl = $this->cache()->get($key);
        $sec = 3600;//1小时
        if($vl){
            $this->cache()->set($key, $vl+1);
        }else{
            $this->cache()->set($key, 1, $sec);
        }
    }

    /**
     * 登录成功，清空
     *
     * @param $mobile
     */
    public function loginErrorClear($mobile)
    {
        $key = "LOGIN_ERROR_CHECK_".$mobile;
        $this->cache()->del($key);
    }

    /**
     * 登陆检查
     * @param $token
     * @param string $source
     * @return mixed
     */
    public function checkLoginToken($token, $source='pc'){
        if($source == 'pc'){
            $user = $this->usersDao->get(['pc_login_token'=>$token]);
        }else{
            $user = $this->usersDao->get(['app_login_token'=>$token]);
        }
//        debug($this->usersDao->getSql());
        if($user) unset($user['passwd'],$user['pc_login_token'],$user['app_login_token']);
        return $user;
    }


}