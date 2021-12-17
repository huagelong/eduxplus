<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/29 17:50
 */

namespace Eduxplus\CoreBundle\Lib\Service\Live;


use Eduxplus\CoreBundle\Lib\Base\BaseService;

/**
 * 1、 添加域名(cname配置，也可以先使用腾讯云自带的域名),推流域名，播放域名，鉴权key设置等
 * 2、生成推流地址
 * 3、生成播放地址
 * 4、添加转码模板,关联到播放域名 [default,sd,hd]
 * 3、直播回放（暂时不做，自己上传录播视频文件）  https://cloud.tencent.com/document/product/267/45074
 *      https://cloud.tencent.com/document/product/454/8681
 * 4、 开通腾讯IM
 *
 * Class TengxunyunLiveService
 * @package Eduxplus\CoreBundle\Lib\Service\Live
 */
class TengxunyunLiveService extends BaseService
{

        /**
         * 生成推流地址
         *
         * @param [type] $streamName
         * @param [type] $time 过期时间
         * @return void
         */
        public function createPushUrl($streamName,$time =0){
            $domain = $this->getOption("app.tengxunyun.live.pushDomain");
            if(!$domain) return "";
            $time = $time?$time:(time()+86400*7);//默认7天过期
            // $time = strtotime("2020-07-16 16:00:00");
            $key = $this->getOption("app.tengxunyun.live.pushDomainKey");
            $txTime = strtoupper(base_convert($time,10,16));
            //txSecret = MD5( KEY + streamName + txTime )
            $txSecret = md5($key.$streamName.$txTime);
            $ext_str = "?".http_build_query(array(
                        "txSecret"=> $txSecret,
                        "txTime"=> $txTime
            ));
            return "rtmp://".$domain."/live/".$streamName . (isset($ext_str) ? $ext_str : "");
        }

        /**
         * 生成播放地址
         * @param $streamName
         * @param int $time
         * @return string
         */
        public function createPlayUrl($streamName, $time =0){
            $domain = $this->getOption("app.tengxunyun.live.playDomain");
            if(!$domain) return "";
            $time = $time?$time:(time()+86400*7);//默认7天过期
            // $time = strtotime("2020-07-16 16:00:00");
            $key = $this->getOption("app.tengxunyun.live.playDomainKey");
            $transcodeTemplateIds = $this->getOption("app.tengxunyun.live.transcodeTemplateId", 1);

            if(!$transcodeTemplateIds) return $this->error()->add("转码模板不能为空");
            $result = [];
            foreach ($transcodeTemplateIds as $transcodeTemplateId){
                $realstreamName = $streamName."_".$transcodeTemplateId;
                $txTime = strtoupper(base_convert($time,10,16));
                $txSecret = md5($key.$realstreamName.$txTime);
                $ext_str = "?".http_build_query(array(
                        "txSecret"=> $txSecret,
                        "txTime"=> $txTime
                    ));
                //http://tlive.2tag.cn/live/test_eduxplus.m3u8
                $result[$transcodeTemplateId] =  "http://".$domain."/live/".$realstreamName.".m3u8" . (isset($ext_str) ? $ext_str : "");
            }
            return $result;
        }


}
