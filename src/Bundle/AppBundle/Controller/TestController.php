<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 14:07
 */

namespace App\Bundle\AppBundle\Controller;

use App\Bundle\AdminBundle\Service\Teach\ChapterService;
use App\Bundle\AppBundle\Lib\Base\BaseHtmlController;
use App\Bundle\AppBundle\Lib\Service\Im\TengxunyunImService;
use App\Bundle\AppBundle\Lib\Service\Live\AliyunLiveService;
use App\Bundle\AppBundle\Lib\Service\Live\TengxunyunLiveService;
use App\Bundle\AppBundle\Lib\Service\Pay\AlipayService;
use App\Bundle\AppBundle\Lib\Service\Vod\AliyunVodService;
use App\Bundle\AppBundle\Lib\Service\Vod\TengxunyunVodService;
use App\Bundle\AppBundle\Service\ImService;
use FOS\RestBundle\Controller\Annotations as Rest;

class TestController extends BaseHtmlController
{

        /**
         * @Rest\Get("/cc/api/test", name="app_test_cc")
         */
        public function ccAction()
        {
                return ["hello world!"];
        }

        /**
         * @Rest\Get("/test", name="app_test")
         */
        public function testAction(TengxunyunVodService $tengxunyunVodService, AliyunVodService $aliyunVodService, AliyunLiveService $aliyunLiveService, ChapterService $chapterService)
        {

                //        $pushUrl = $aliyunLiveService->createPushUrl("testVideo");
                //        var_dump($pushUrl);
                //        $playUrl = $aliyunLiveService->createPlayUrl("testVideo");
                //        var_dump($playUrl);
                $rs = $chapterService->ayncTranscode(2, 2, 'a8bf0733774a42869dbca5a0f1a54555');
                if ($this->error()->has()) {
                        exit($this->error()->getLast());
                }
                var_dump($rs);
                exit;

                //        $tengxunyunVodService->getVodEncryptionPlayUrl('');
                //        exit;
                $data = [];
                //        $data['signature'] = $tengxunyunVodService->getUploadSignature();
                //        $data['appId'] = $tengxunyunVodService->getAppId();
                //        $play = $aliyunVodService->getVodPlayInfo("692f777454174d19ab3b0d5cf806b4d2");
                //        $data['userId'] = $aliyunVodService->getConfigUserId();


                //        if($this->error()->has()){
                //            exit($this->error()->getLast());
                //        }

                //        $data['palyAuth'] = $play['playAuth'];
                $data['source']  =  'http://aliyunliveplay.2tag.cn/live/testVideo.m3u8?auth_key=1594108856-0-0-8d1328916f468d58cf5bf5ec9c8d17f6';
                //        $data['token'] = $tengxunyunVodService->getAndvancePlaySign("5285890804485866557");
                //        $data['fileid'] = '5285890804485866557';
                //        exit($data['token']);
                //        $data['token'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBJZCI6MTI1NTMxMDY4NSwiZmlsZUlkIjoiNTI4NTg5MDgwNDQ3NjMzNTcwMiIsImN1cnJlbnRUaW1lU3RhbXAiOjE1OTI5NzAzOTksImV4cGlyZVRpbWVTdGFtcCI6MTU5MzA1Njc5OSwidXJsQWNjZXNzSW5mbyI6eyJ0IjoiNWVmNDFlMWYiLCJ1cyI6IjVlZjJjYzlmY2VmMTgifX0.4ROsPaOOiDytAHrP5ehHCwInVIt46FZUsThSFSPI8WQ';
                return $this->render('@AppBundle/test/test.html.aliyun_live.twig', $data);
        }


        /**
         * @Rest\Get("/test_live", name="app_test_live")
         */
        public function testLiveAction(TengxunyunLiveService $tengxunyunLiveService, TengxunyunImService $tengxunyunImService)
        {
                $data = [];
                // $pushUrl = $tengxunyunLiveService->createPushUrl("test");
                // $playUrl = $tengxunyunLiveService->createPlayUrl("test");
                // var_dump($pushUrl);
                // var_dump($playUrl);
                // exit();
//                $sql = "SELECT a FROM App:BaseUser a WHERE a.id=1";
//                $user = $this->fetchOne($sql);
//                $rs = $tengxunyunImService->accountCheck($user['uuid']);
//                if ($this->error()->has()) {
//                        exit($this->error()->getLast());
//                }
//
//                var_dump($rs);
//                exit;
                $data = [];
                return $this->render('@AppBundle/test/test.html.tengxunyun_live.twig', $data);
        }

        /**
         * @Rest\Get("/test_im", name="app_test_im")
         */
        public function testImAction(ImService $imService)
        {
                $data = [];
                $sql = "SELECT a FROM App:BaseUser a WHERE a.id=1";
                $user = $this->fetchOne($sql);
                $uuid = $user['uuid'];
                $chapterId = 1;
                //初始化用户
                $imService->initUser($user['id']);
                //初始化群组
                $groupId = $imService->initGroup($chapterId);
                $imService->addGroupMember($groupId, $uuid);

                $sign = $imService->createUserSig($uuid);
                if ($this->error()->has()) {
                        exit($this->error()->getLast());
                }
                $data['sign'] = $sign;
                $data['sdkAppID'] = $imService->getSDKAppID();
                $data['uuid'] = $uuid;
                $data['groupId'] = $groupId;
                return $this->render('@AppBundle/test/test.html.im.twig', $data);
        }

        /**
         * @Rest\Get("/test_im2", name="app_test_im2")
         */
        public function testIm2Action(ImService $imService)
        {
                $data = [];
                $sql = "SELECT a FROM App:BaseUser a WHERE a.id=2";
                $user = $this->fetchOne($sql);
                $uuid = $user['uuid'];
                $chapterId = 1;
                //初始化用户
                $imService->initUser($user['id']);
                //初始化群组
                $groupId = $imService->initGroup($chapterId);
                $imService->addGroupMember($groupId, $uuid);

                $sign = $imService->createUserSig($uuid);
                if ($this->error()->has()) {
                        exit($this->error()->getLast());
                }
                $data['sign'] = $sign;
                $data['sdkAppID'] = $imService->getSDKAppID();
                $data['uuid'] = $uuid;
                $data['groupId'] = $groupId;
                return $this->render('@AppBundle/test/test.html.im.twig', $data);
        }


        /**
         * @Rest\Get("/alipay", name="app_test_alipay")
         */
        public function alipayAction(AlipayService $alipayService){
            $data = [];
            $subject = "测试商品";
            $outTradeNo = "O".time();
            $totalAmount = "0.01";
            $returnUrl = "http://dev.eduxplus.com";
            $body = $alipayService->pagePay($subject, $outTradeNo, $totalAmount, $returnUrl);
            $data['body'] = $body;
            return $this->render('@AppBundle/test/alipay.html.twig', $data);
        }
}
