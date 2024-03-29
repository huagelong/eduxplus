<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/15 11:15
 */

namespace Eduxplus\WebsiteBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\CoreBundle\Lib\Service\Base\Vod\AliyunVodService;
use Eduxplus\CoreBundle\Lib\Service\Base\Vod\TengxunyunVodService;
use Eduxplus\EduxBundle\Service\Teach\ImService;
use Eduxplus\WebsiteBundle\Service\LearnService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LearnController extends BaseHtmlController
{

    public function indexAction($page=1, LearnService $learnService)
    {
        $page = $page?$page:1;
        $pageSize = 40;
        $uid = $this->getUid();

        $data = [];
        list($pagination, $list) = $learnService->getList($uid, $page, $pageSize);
        $count = $learnService->getCourseCount($uid);
        $data['list'] = $list;
        $data['pagination'] = $pagination;
        $data['count'] = $count;
        return $this->render('@WebsiteBundle/learn/index.html.twig', $data);
    }

    public function courseListAction($courseId, LearnService $learnService)
    {
        list($rs, $pathCount) = $learnService->getChapterTree($courseId, 0);
        $data = [];
        $data['courseInfo'] = $learnService->getCourseInfo($courseId);
        $data['chapterTree'] = $rs;
        $data['pathCount'] = $pathCount;
        $data['route'] = "app_user_home";
        return $this->render('@WebsiteBundle/learn/courseList.html.twig', $data);
    }

    public function detailAction($chapterId,
                                 LearnService $learnService,
                                 AliyunVodService $aliyunVodService,
                                 TengxunyunVodService $tengxunyunVodService,
                                 ImService $imService
    )
    {
        $uid = $this->getUid();
        if(!$learnService->checkChapterCanView($chapterId, $uid)){
            return $this->showMsg("你没有权限观看本教程");
        }

        if(!$learnService->checkBlock($chapterId, $uid)){
            return $this->showMsg("你必须请先学习完上一节课程，才能继续学习!");
        }

        $info = $learnService->getChapter($chapterId);
        $videoInfo = $learnService->getVideoById($chapterId);
        list($rs, $pathCount) = $learnService->getChapterTree($info['courseId']);
        $data = [];
        $data['courseInfo'] = $learnService->getCourseInfo($info['courseId']);
        $data['route'] = "app_user_home";
        $data["chapterId"] = $chapterId;

//        $info['openTime'] = time()-1000;
//        $info['isOpen'] = 1;
        $data['info'] = $info;
        $data['openTimeY'] = $info['openTime']?date('Y', $info['openTime']):0;
        $data['openTimeM'] = $info['openTime']?date('m', $info['openTime']):0;
        $data['openTimeD'] = $info['openTime']?date('d', $info['openTime']):0;
        $data['openTimeH'] = $info['openTime']?date('H', $info['openTime']):0;
        $data['openTimeI'] = $info['openTime']?date('i', $info['openTime']):0;
        $data['openTimeS'] = $info['openTime']?date('s', $info['openTime']):0;

        $data['nowTimeY'] = date('Y');
        $data['nowTimeM'] = date('m');
        $data['nowTimeD'] = date('d');
        $data['nowTimeH'] = date('H');
        $data['nowTimeI'] = date('i');
        $data['nowTimeS'] = date('s');
        $min20 = 20*60;
        $data['canNotChat'] = !($info['openTime']?($info['openTime']-time()<$min20):false);
        $data['chapterTree'] = $rs;
        $data['pathCount'] = $pathCount;
        $data['videoInfo'] = $videoInfo;
        $data["isLiveVod"] = 0;
        if($videoInfo){
            $adapter = $videoInfo['videoChannel'];
            $liveVodData = $learnService->parseLiveData($videoInfo['liveData'], $adapter);
            //点播
            if(($videoInfo["type"] == 2)){
                $data['videoId'] = $videoInfo['videoId'];
                if ($adapter == 1) {
                    $data['appId'] = $tengxunyunVodService->getAppId();
                    $data['token'] = $tengxunyunVodService->getAndvancePlaySign($data['videoId']);
                }

                if ($adapter == 2) {
                    $play = $aliyunVodService->getVodPlayInfo($videoInfo["videoId"]);
                    $data['palyAuth'] = $play['playAuth'];
                }
                //判断是否是直播回放
                $data["isLiveVod"] = $liveVodData?1:0;
            }else{ //直播
                //统一用腾讯云im
                $user = $this->getUserInfo();
                $uuid = $user['id'];
                //初始化用户
                $imService->initUser($user['id']);
                //初始化群组
                $groupId = $imService->initGroup($chapterId);
                $imService->addGroupMember($groupId, $uuid);
                $sign = $imService->createUserSig($uuid);
                $data['sign'] = $sign;
                $data['sdkAppID'] = $imService->getSDKAppID();
                $data['uuid'] = $uuid;
                $data['groupId'] = $groupId;

                $playUrl = isset($liveVodData["playUrl"])?$liveVodData["playUrl"]:"";
                $playUrl = $playUrl?json_encode($playUrl):(object) null;
                $data['playUrl'] = $playUrl;

            }
        }
        //保存观看记录  todo 怎么保证已经看完
        $learnService->addStudyLog($chapterId, $uid);
        
        return $this->render('@WebsiteBundle/learn/detail.html.twig', $data);
    }
}
