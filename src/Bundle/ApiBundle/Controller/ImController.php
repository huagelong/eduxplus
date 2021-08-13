<?php


/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\ApiBundle\Controller;

use App\Bundle\AppBundle\Lib\Base\BaseApiController;
use App\Bundle\AppBundle\Service\ImService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Bundle\ApiBundle\Controller
 */
class ImController extends BaseApiController
{

    /**
     *  im设置/取消禁言
     * 
     * @Rest\Post("/im/forbidSendMsg",name="api_im_forbidSendMsg")
     */
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
