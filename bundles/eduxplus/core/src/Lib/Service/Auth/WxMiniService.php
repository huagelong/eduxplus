<?php

namespace Eduxplus\CoreBundle\Lib\Service\Auth;

use Eduxplus\CoreBundle\Lib\Base\BaseService;

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

use Eduxplus\CoreBundle\Lib\Service\UploadService;
use EasyWeChat\Factory;
use Eduxplus\CoreBundle\Service\UserService;
use Eduxplus\CoreBundle\Lib\Service\File\AliyunOssService;
use Eduxplus\CoreBundle\Entity\BaseOpenAuth;

class WxMiniService extends BaseService
{
    protected $userService;
    protected $uploadService;

    public function __construct(UserService $userService, UploadService $uploadService)
    {
        $this->userService = $userService;
        $this->uploadService = $uploadService;
    }

    public function client()
    {
        $config = [
            'app_id' => $this->getOption("app.wxmini.appid"),
            'secret' => $this->getOption("app.wxmini.secret"),
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => $this->getBasePath() . '/var/log/wechat.log',
            ],
        ];
        $app = Factory::miniProgram($config);
        return $app;
    }

    /**
     * 登录
     *
     * @param [type] $code
     * @return void
     */
    public function login($code, $source = "wxmini")
    {
        $app = $this->client();
        $data = $app->auth->session($code);
        // var_dump($data);
        if (isset($data["errcode"])) {
            return $this->error()->add($data['errmsg']);
        }
        $openId = isset($data["openid"]) ? $data["openid"] : "";
        $sessionKey = isset($data["session_key"]) ? $data["session_key"] : "";
        //找到用户
        $data = [];
        $data['openId'] = $openId;
        $data['sessionKey'] = $sessionKey;
        $data['token'] = "";

        if ($openId) {
            $sql = "SELECT a FROM App:BaseOpenAuth a WHERE a.openId =:openId AND a.type=:type";
            $openAuth = $this->fetchOne($sql, ["openId" => $openId, "type" => $source]);
            if ($openAuth) {
                $uid = $openAuth["uid"];
                $token = $this->userService->setLogin($uid, $source);
                $data['token'] = $token;
                return $data;
            }
        }
        return $data;
    }


    /**
     * 注册
     *
     * @param [type] $nickname
     * @param [type] $avatarUrl
     * @param [type] $openId
     * @return void
     */
    public function registerAndLogin($nickname, $avatarUrl, $openId, $sex, $mobile, $source = "wxmini")
    {
        $fullName = "";

        $fileName = session_create_id("img") . ".png";
        $filePath = $this->getBasePath() . "/var/tmp/" . $fileName;
        $data = file_get_contents($avatarUrl);
        file_put_contents($filePath, $data);
        $remoteFilePath = "gravatar/" . date('Y/m/d') . "/" . $fileName;

        $gravatar = $this->uploadService->up($remoteFilePath, $filePath);
        $uid = $this->userService->add($mobile, $fullName, $nickname, $sex, $gravatar, $source);
        if (!$uid) return $this->error()->add("添加新用户失败!");
        //更新openid
        $sql = "SELECT a FROM App:BaseOpenAuth a WHERE a.openId =:openId AND a.type=:type";
        $openAuth = $this->fetchOne($sql, ["openId" => $openId, "type" => $source]);
        if (!$openAuth) {
            $openAuthModel = new BaseOpenAuth();
            $openAuthModel->setUid($uid);
            $openAuthModel->setOpenId($openId);
            $openAuthModel->setType($source);
            $this->save($openAuthModel);
        }
        //登录
        $token = $this->userService->setLogin($uid, $source);
        return $token;
    }
}
