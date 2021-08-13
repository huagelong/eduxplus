<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace App\Bundle\ApiBundle\Controller;

use App\Bundle\ApiBundle\Service\GoodService;
use App\Bundle\AppBundle\Lib\Base\BaseApiController;
use App\Bundle\AppBundle\Service\LearnService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotations;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Bundle\ApiBundle\Controller
 */
class UserController extends BaseApiController
{
    /**
     * @Rest\Post("/my/meinfo", name="api_user_meinfo")
     */
    public function meinfoAction()
    {
        $userInfo = $this->getUserInfo();
        if (!$userInfo) return [];
        $data = [];
        $data["uuid"] = $userInfo["uuid"];
        $data["displayName"] = $userInfo["displayName"];
        $data["fullName"] = $userInfo["fullName"];
        $data["gravatar"] = $userInfo["gravatar"];
        $data["birthday"] = $userInfo["birthday"];
        $data["sex"] = $userInfo["sex"];
        $data["realRole"] = $userInfo["realRole"];
        return $data;
    }

    /**
     * @Rest\Post("/my/mecourse", name="api_user_mecourse")
     */
    public function mecourseAction(Request $request, GoodService $goodService){
        $page = (int) $request->get("p", 1);
        $page = $page<1?1:$page;
        $uid = $this->getUid();
        $pageSize = 40;
        list($totalCount, $totalPage, $list) = $goodService->getUidCourse($uid, $page, $pageSize);
        return ["totalCount"=>$totalCount, "totalPage"=>$totalPage, "list"=>$list];
    }

    /**
     * 课程列表
     *
     * @Rest\Post("/my/courseList", name="api_user_courseList")
     */
    public function courseListAction(Request $request, LearnService $learnService){
        $courseId = $request->get("courseId");
        if(!$courseId) return $this->responseError("参数输入错误!");
        list($rs, $pathCount) = $learnService->getChapterTree($courseId, 0);
        $data = [];
        $data['courseInfo'] = $learnService->getCourseInfo($courseId);
        $data['chapterTree'] = $rs;
        $data['pathCount'] = $pathCount;
        return $data;
    }


}
