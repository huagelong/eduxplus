<?php


/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace Eduxplus\ApiBundle\Controller;

use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use Eduxplus\WebsiteBundle\Service\ImService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package Eduxplus\ApiBundle\Controller
 */
class ImController extends BaseApiController
{

    
    public function forbidSendMsgAction(Request $request, ImService $imService)
    {
        $groupId = $request->get("groupId");
        $uuid = $request->get("uuid");
        $isForbid = (int) $request->get("forbid");

        $shutUpTime = 0;
        if ($isForbid) {
            $shutUpTime = 3600;
        }

        $uuids = [$uuid];
        $imService->forbidSendMsg($groupId, $uuids, $shutUpTime);

        if ($this->error()->has()) {
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseSuccess([], "操作成功!");
    }
}
