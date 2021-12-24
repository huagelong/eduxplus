<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/10 11:02
 */

namespace Eduxplus\ApiBundle\Controller;

use Eduxplus\CoreBundle\Lib\Service\Vod\AliyunVodService;
use Eduxplus\CoreBundle\Lib\Service\Vod\TengxunyunVodService;
use Eduxplus\WebsiteBundle\Service\ImService;
use Eduxplus\WebsiteBundle\Service\LearnService;
use Symfony\Component\Routing\Annotation\Route;
use Eduxplus\CoreBundle\Lib\Base\BaseApiController;
use Symfony\Component\HttpFoundation\Request;

class LearnController extends BaseApiController
{

    /**
     * 课程详情
     *
     * @Route("/my/learn", name="api_learn_index")
     */
    public function indexAction(Request $request, LearnService $learnService,
                                 TengxunyunVodService $tengxunyunVodService,
                                AliyunVodService $aliyunVodService,
                                ImService $imService
    ){
        $chapterId = $request->get("chapterId");
        if(!$chapterId) return $this->responseError("参数错误!");
//        $videoInfo = $learnService->getVideoById($chapterId);
        $info = $learnService->getChapter($chapterId);
//        list($rs, $pathCount) = $learnService->getChapterTree($info['courseId']);
        $videoInfo = isset($info["video"])?$info["video"]:[];
        $data = [];
        $data["chapterId"] = $chapterId;
        $data['info'] = $info;
//        $data['chapterTree'] = $rs;
//        $data['pathCount'] = $pathCount;
        $data['videoInfo'] = $videoInfo;
        $data["isLiveVod"] = 0;
        if($videoInfo){

            $liveVodData = $learnService->parseLiveData($videoInfo['liveData'], $info['videoChannel']);
            //点播
            if(($videoInfo["type"] == 2)){
                $data['videoId'] = $videoInfo['videoId'];
//                $adapter = $this->getOption("app.vod.adapter");
                $adapter = $videoInfo['videoChannel'];
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
                $urlArr = $tengxunyunVodService->getVodEncryptionPlayUrl($data['videoId']);
                if(!$urlArr) return $this->responseError($this->error()->getLast());
                $data['vodUrl'] = $urlArr[0];
                $data['vodCoverUrl'] = $urlArr[1];
            }else{ //直播待处理
                $adapter = $videoInfo['videoChannel'];
                //统一用腾讯云im
                $user = $this->getUserInfo();
                $uuid = $user['uuid'];
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
        return $this->responseSuccess($data);
    }
}
