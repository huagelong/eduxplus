<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 15:41
 */

namespace Eduxplus\EduxBundle\Controller\Admin;


use Eduxplus\CoreBundle\Lib\Service\Base\Vod\AliyunVodService;
use Eduxplus\EduxBundle\Service\Mall\GoodsService;
use Eduxplus\EduxBundle\Service\Teach\ProductService;
use Eduxplus\EduxBundle\Service\Teach\StudyPlanService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\EduxBundle\Service\Teach\ImService;
use Eduxplus\EduxBundle\Service\Teach\ChapterService;
use Firebase\JWT\JWT;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class GlobController extends BaseAdminController
{
  
    
    public function searchProductDoAction(Request $request, ProductService $productService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $productService->searchProductName($kw);
        return $this->responseSuccess($data);
    }

    
    public function searchGoodsDoAction(Request $request, GoodsService $goodsService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $goodsService->searchGoodsName($kw);
        return $this->responseSuccess($data);
    }

    
    public function searchCourseDoAction(Request $request, StudyPlanService $studyPlanService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $studyPlanService->searchCourseName($kw);
        return $this->responseSuccess($data);
    }


    public function tengxunyunLiveCallbackAction(Request $request, ImService $imService, LoggerInterface $logger)
    {
        //http://dev.eduxplus.com/tengxunyunImCallback?CallbackCommand=Group.CallbackAfterSendMsg&ClientIP=116.230.7.188&OptPlatform=Web&SdkAppid=1400399479&contenttype=json
        $callbackCommand = $request->get("CallbackCommand");
        $clientIP = $request->get("ClientIP");
        $optPlatform = $request->get("OptPlatform");
        $sdkAppid = $request->get("SdkAppid");
        $mySdkAppid = $imService->getOption("app.tengxunyun.im.sdkAppid");
        if ($sdkAppid != $mySdkAppid) return new Response("error");
        //保存聊天记录
        if ($callbackCommand == "Group.CallbackAfterSendMsg") {
            $content = $request->getContent();
            $arr = json_decode($content, true);
            $groupId = $arr['GroupId'];
            $msgTime = $arr['MsgTime'];
            $imService->saveChatLog($groupId, $clientIP, $optPlatform, $msgTime, $content);
            /**
             * {"CallbackCommand":"Group.CallbackAfterSendMsg","From_Account":"e104c7f8-c31c-4c40-92fe-63fa67153619","GroupId":"@TGS#37GAYNTGC","MsgBody":[{"MsgContent":{"Text":"asdasda"},"MsgType":"TIMTextElem"}],"MsgSeq":11,"MsgTime":1595402472,"Operator_Account":"e104c7f8-c31c-4c40-92fe-63fa67153619","Random":82344382,"Type":"ChatRoom"}
             */
//            $logger->info($content);
        }
        return new Response("ok");
    }


    public function vodCallbackAction(
        $type,
        Request $request,
        AliyunVodService $aliyunVodService,
        ChapterService $chapterService,
        LoggerInterface $logger
    ) {
        $channel = 0;
        $videoId = 0;
        if ($type == "aliyun") {
            $eventType = "";
            $url = $request->getUri();
            /**
             * 8:00] app.INFO: {"accept-encoding":["gzip,deflate"],"user-agent":["Apache-HttpClient\/4.4.1 (Java\/1.8.0_152)"],"connection":["Keep-Alive"],"host":["dev.eduxplus.com"],"content-length":["1532"],"x-vod-signature":["4fac8288f9ebf787d1e146f1443d6dc6"],"x-vod-timestamp":["1594363793"],"content-type":["application\/json"],"x-php-ob-level":["0"]} [] []
             */
            if (!$aliyunVodService->callBackSignatureCheck($url)) return $this->responseError("签名不通过!");;
            $body =  $request->getContent();
            /**
             * {"Status":"success","VideoId":"c28ec065ed4a4eceb4eff8f18b928533","EventType":"TranscodeComplete","EventTime":"2020-07-10T06:49:51Z","StreamInfos":[{"Status":"success","IsAudio":false,"Size":21437828,"Definition":"SD","Fps":"25","StartTime":"2020-07-10T06:49:24Z","Duration":154.0,"Bitrate":"1110","Encrypt":true,"FileUrl":"http://outin-8d35fbc9baba11eaa07700163e12ac16.oss-cn-shenzhen.aliyuncs.com/c28ec065ed4a4eceb4eff8f18b928533/0a9fa5863c818758a3d8d541020f26c9-sd-encrypt-stream.m3u8","Format":"m3u8","FinishTime":"2020-07-10T06:49:51Z","Height":720,"Width":1280,"JobId":"4f6930223ca748bcbbd3369654096d1f"},{"Status":"success","IsAudio":false,"Size":8128368,"Definition":"FD","Fps":"25","StartTime":"2020-07-10T06:49:24Z","Duration":154.0,"Bitrate":"421","Encrypt":true,"FileUrl":"http://outin-8d35fbc9baba11eaa07700163e12ac16.oss-cn-shenzhen.aliyuncs.com/c28ec065ed4a4eceb4eff8f18b928533/7fc3f186efc8f18e5ca5b356055c0740-fd-encrypt-stream.m3u8","Format":"m3u8","FinishTime":"2020-07-10T06:49:42Z","Height":360,"Width":640,"JobId":"2ca0bdafcf034639b7b4e711c944b40a"},{"Status":"success","IsAudio":false,"Size":15046204,"Definition":"LD","Fps":"25","StartTime":"2020-07-10T06:49:24Z","Duration":154.0,"Bitrate":"779","Encrypt":true,"FileUrl":"http://outin-8d35fbc9baba11eaa07700163e12ac16.oss-cn-shenzhen.aliyuncs.com/c28ec065ed4a4eceb4eff8f18b928533/5f185ddf4c24c56c8484e71a96cce95a-ld-encrypt-stream.m3u8","Format":"m3u8","FinishTime":"2020-07-10T06:49:46Z","Height":540,"Width":960,"JobId":"88bba7af46ad43c2a8ed08fb0f2b46ff"}]}
             */
//            $logger->info($body);
            $json = json_decode($body, true);
            if (isset($json['Status']) && ($json['Status'] == 'success')) {
                $videoId = $json['VideoId'];
                $channel = 2;
                $eventType = $json['EventType'];
            }
            //转换成功
            if ($videoId && $eventType == 'TranscodeComplete') {
                $chapterService->updateVideoStatus($channel, $videoId);
            }
        }

        if ($type == "tengxunyun") {
            $body =  $request->getContent();
            $logger->info($body);
            $json = json_decode($body, true);
            $status = $json['ProcedureStateChangeEvent']["Status"];
            $msg = $json['ProcedureStateChangeEvent']['Message'];
            if (($status == "FINISH") && ($msg == "SUCCESS")) {
                $eventType = $json['EventType'];
                $videoId = $json['ProcedureStateChangeEvent']['FileId'];
                $channel = 1;
                //转码完成
                if ($videoId && ($eventType == "ProcedureStateChanged")) {
                    $chapterService->updateVideoStatus($channel, $videoId);
                    $videoInfo = $chapterService->getVideoByVideoId($videoId);
                    if(!$videoInfo) return ;
                    $chapterInfo = $chapterService->getChapter($videoInfo["chapterId"]);
                    if($chapterInfo["coverImg"]) {
                        $coverImgArr = json_decode($chapterInfo["coverImg"], true);
                        $coverImg = current($coverImgArr);
                        $chapterService->modifyCoverImg($coverImg, 2, 1, $videoId);
                    }
                }
            }
        }



        return $this->responseSuccess("");
    }

    public function aliyunVodPlayCheckAction(Request $request, AliyunVodService $aliyunVodService, ChapterService $chapterService)
    {
//        $token = $request->get("MtsHlsUriToken");
        $token = $request->get("Ciphertext");

        if (!$token) return new Response("error");
        $key = $chapterService->getConfig("secret");
        $data = (array) JWT::decode($token, $key,  array('HS256'));
        $videoId = $data['videoId'];

        if (!$videoId) return new Response("error");
        $info = $chapterService->getVideoByVideoId($videoId);
        if (!$info)  return new Response("error");
        // $logger->info(json_encode($info));
        if (!isset($info['vodData']) || !$info['vodData']) return new Response("error");
        $vodData = json_decode($info['vodData'], true);
        if (!isset($vodData['aliyunVod']) || !$vodData['aliyunVod']) return new Response("error");
        list($cipherText, $plaintext) = $vodData['aliyunVod'];
        // $plaintext = $aliyunVodService->decrypt($cipherText);
        // $plaintext = $plaintext['Plaintext'];
        // $logger->info(json_encode($plaintext));
        return new Response(base64_decode($plaintext));
    }


}
