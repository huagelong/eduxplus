<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/28 19:38
 */

namespace Eduxplus\ApiBundle\Controller;

use Eduxplus\ApiBundle\Service\GoodService;
use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use Eduxplus\WebsiteBundle\Service\LearnService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package Eduxplus\ApiBundle\Controller
 */
class UserController extends BaseApiController
{
    /**
     * @Route("/my/meinfo", name="api_user_meinfo")
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
     * @Route("/my/mecourse", name="api_user_mecourse")
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
     * @Route("/my/courseList", name="api_user_courseList")
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
