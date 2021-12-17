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
 *  1、添加播流，推流域名(直播中心，点播中心地区要一致)
 *  2、关联推流域名
 *  3、配置鉴权（推流域名，播放域名都要处理）
 *  4、设置 推流域名鉴权KEY，播放域名鉴权KEY
 *  5、播流域名配置录像转存vod
 *  6、配置录制文件生成回调配置
 *
 *
 * Class AliyunLiveService
 * @package Eduxplus\CoreBundle\Lib\Service\Live
 */
class AliyunLiveService extends BaseService
{

    /**
     * 生成推流网址
     * @param $streamName
     */
    public function createPushUrl($streamName, $exp=0){
        $pushDomain = $this->getOption("app.aliyun.live.pushDomain");
        $key = $this->getOption("app.aliyun.live.pushDomainKey");
        $time = time();
        $exp = $exp?$exp:($time+86400*7);//7天过期
        $url = "rtmp://{$pushDomain}/live/".$streamName;
        $authKey = $this->createAuthKey($url, $key, $exp);
        $url = $url."?auth_key=".$authKey;
        return $url;
    }

    /**
     * 生成播放网址
     *
     * @param $streamName
     * @return array
     */
    public function createPlayUrl($streamName, $exp=0){
        $playDomain = $this->getOption("app.aliyun.live.playDomain");
        $key = $this->getOption("app.aliyun.live.playDomainKey");
        $time = time();
        $exp = $exp?$exp:($time+86400*7);//7天过期
        $transcodeTemplateId = $this->getOption("app.aliyun.live.transcodeTemplateId");
//        $url = "rtmp://{$playDomain}/live/".$streamName."_{$transcodeTemplateId}";
        //原画
        $url = "http://{$playDomain}/live/".$streamName.".m3u8";
        $authKey = $this->createAuthKey($url, $key, $exp);
        $odUrl = $url."?auth_key=".$authKey;
        $data = [];
        $data['OD'] = $odUrl;
        if($transcodeTemplateId){
            if($this->isNotJson($transcodeTemplateId)){
                return $this->error()->add("app.aliyun.live.transcodeTemplateId 必须是json");
            }else{
                $transcodeTemplateIdArr = json_decode($transcodeTemplateId, true);
                foreach ($transcodeTemplateIdArr as $k=>$v){
                    $url = "http://{$playDomain}/live/".$streamName."_".$v.".m3u8";
                    $authKey = $this->createAuthKey($url, $key, $exp);
                    $urlTmp = $url."?auth_key=".$authKey;
                    $data[$k] = $urlTmp;
                }
            }
        }
        return $data;
    }


    /**
     * 生成直播key
     * @param $url
     */
    protected function createAuthKey($url, $key, $exp){
        $pathinfo = parse_url($url);
        $path = isset($pathinfo['path'])?$pathinfo['path']:"/";
        $rand = "0";
        $uid = "0";
        $sstring = $path."-".$exp."-".$rand."-".$uid."-".$key;
        $hashvalue = md5($sstring);
        $authKey ="{$exp}-{$rand}-{$uid}-{$hashvalue}";
        return $authKey;
    }

    function isNotJson($str){
        return is_null(json_decode($str));
    }

}
