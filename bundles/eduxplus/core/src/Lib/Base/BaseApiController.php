<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace Eduxplus\CoreBundle\Lib\Base;


class BaseApiController extends BaseController
{
    protected $apiBaseService;

    public function __construct(ApiBaseService $apiBaseService)
    {
        $this->apiBaseService = $apiBaseService;
    }

    public function getUid()
    {
        $request = $this->apiBaseService->request();
        $clientId = $request->headers->get('X-AUTH-CLIENT-ID');
        $token = $request->headers->get('X-AUTH-TOKEN');
        if (!$clientId || !$token) return 0;
        $userInfoObj = $this->apiBaseService->getUserByToken($token, $clientId);
        if (!$userInfoObj) return 0;
        return $userInfoObj->getId();
    }

    public function getUserInfo()
    {
        $uid = $this->getUid();
        $sql = "SELECT a FROM Core:BaseUser a WHERE a.id = :id";
        $model = $this->apiBaseService->fetchOne($sql, ["id"=>$uid]);
        return $model;
    }
}
