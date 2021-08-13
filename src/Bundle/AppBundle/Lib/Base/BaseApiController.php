<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace App\Bundle\AppBundle\Lib\Base;

use App\Exception\NeedLoginException;
use Jobby\Exception;

class BaseApiController extends BaseController
{

    public function getUid()
    {
        $request = $this->request();
        $clientId = $request->headers->get('X-AUTH-CLIENT-ID');
        $token = $request->headers->get('X-AUTH-TOKEN');
        if (!$clientId || !$token) return 0;
        $userInfoObj = $this->getUserByToken($token, $clientId);
        if (!$userInfoObj) return 0;
        return $userInfoObj->getId();
    }
}
