<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/22 17:06
 */

namespace Eduxplus\CoreBundle\Lib\Service\Base\Vod;


use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Service\CacheService;
use Eduxplus\CoreBundle\Lib\Utils;
use Firebase\JWT\JWT;
use TencentCloud\Cme\V20191029\Models\DeleteClassRequest;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Vod\V20180717\Models\DescribeMediaInfosRequest;
use TencentCloud\Vod\V20180717\Models\ModifyClassRequest;
use TencentCloud\Vod\V20180717\Models\ModifyMediaInfoRequest;
use TencentCloud\Vod\V20180717\VodClient;
use TencentCloud\Vod\V20180717\Models\CreateClassRequest;
use TencentCloud\Vod\V20180717\Models\ProcessMediaByProcedureRequest;

/**
 * 分发播放设置->域名管理->编辑->Key 防盗链(启动)
 *
 *
 * Class TengxunyunVodService
 * @package Eduxplus\CoreBundle\Lib\Service\Vod
 */
class TengxunyunVodService extends BaseService
{

    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    protected $secretId;
    protected $secretKey;
    protected $region;
    protected $appId;

    public function initConfig()
    {
        $this->secretId = $this->getOption("app.tengxunyun.secretId");
        $this->secretKey = $this->getOption("app.tengxunyun.secretKey");
        $this->region = $this->getOption("app.tengxunyun.region");
        $this->appId = $this->getOption("app.tengxunyun.appId");
    }

    public function getAppId()
    {
        return (int) $this->getOption("app.tengxunyun.appId");
    }

    public function getRegion(){
        return $this->getOption("app.tengxunyun.region");
    }

    /**
     * 获得超级播放器签名
     *
     * @param $videoId
     */
    public function getAndvancePlaySign($videoId)
    {
        $key = $this->getOption("app.tengxunyun.vod.encryptionkey");
        if (!$key) return "";
        $superPlayer = $this->getOption("app.tengxunyun.vod.superPlayer");
        $superPlayer = $superPlayer?$superPlayer:"basicDrmPreset";
        $appId = $this->getAppId();
        $currentTimeStamp = time();
        $expireTimeStamp = $currentTimeStamp + 3600 * 24;
        $payLoadArr = [];
        $payLoadArr['appId'] = $appId;
        $payLoadArr['fileId'] = $videoId;
        $payLoadArr['currentTimeStamp'] = $currentTimeStamp;
        $payLoadArr['expireTimeStamp'] = $expireTimeStamp;
        $payLoadArr['pcfg'] = $superPlayer;  //默认播放器，清晰度切换用
        $payLoadArr['urlAccessInfo']['t'] = dechex($currentTimeStamp + 3600 * 24); //24小时过期
        $payLoadArr['urlAccessInfo']['us'] = uniqid();
        $token = JWT::encode($payLoadArr, $key);
        return $token;
    }

    /**
     * 获取播放信息
     * @param $videoId
     * @return bool|mixed
     */
    public function getPlayInfo($videoId){
        $cacheKey = "TengxunyunVodService:getPlayInfo:".$videoId;
        $data = $this->cacheService->get($cacheKey);
        if($data) return $data;

        $appId = $this->getAppId();
        $token = $this->getAndvancePlaySign($videoId);
        $getplayinfo = "http://playvideo.qcloud.com/getplayinfo/v4/{$appId}/{$videoId}?psign=$token";
        $result = $this->baseCurlGet($getplayinfo, "get");
        if(!$result) return $this->error()->add("获取播放信息失败!");
        $resultJson = json_decode($result, true);
        if($resultJson['code']>0) return $this->error()->add("获取播放信息失败!");

        $this->cacheService->set($cacheKey, $resultJson, 3600);

        return $resultJson;
    }

    /**
     * 获取vod播放地址
     * @param $playUrl
     */
    public function getVodEncryptionPlayUrl($videoId)
    {
        $key = $this->getOption("app.tengxunyun.vod.encryptionkey");
        if (!$key) return "";
        //获取播放信息
        $resultJson = $this->getPlayInfo($videoId);
        if(!$resultJson) return false;
        $coverUrl = $resultJson['media']['basicInfo']['coverUrl'];
        $playUrl = $resultJson['media']['streamingInfo']['drmOutput'][0]['url'];
        $drmToken = $resultJson['media']['streamingInfo']['drmToken'];

        $urlInfo = parse_url($playUrl);
        $baseName = basename($urlInfo['path']);
        $dir = dirname($urlInfo['path']) . "/";
        $t = dechex(time() + 3600 * 24); //24小时过期
        $us = uniqid();
        $sign = md5($key . $dir . $t . $us);
        $pathinfo = pathinfo($playUrl);
        $dirName = $pathinfo['dirname'];

        $realPlayUrl = $dirName."/voddrm.token.".$drmToken.".".$baseName."?t={$t}&us={$us}&sign={$sign}";
        return [$realPlayUrl, $coverUrl];
    }

    /**
     * 获取客户端上传签名
     */
    public function getUploadSignature()
    {
        $this->initConfig();
        // 确定签名的当前时间和失效时间
        $current = time();
        $expired = $current + 86400;  // 签名有效期：1天

        // $procedureName = $this->getOption("app.tengxunyun.vod.procedure");
        // 向参数列表填入参数
        $arg_list = array(
            "secretId" => $this->secretId,
            "currentTimeStamp" => $current,
            "expireTime" => $expired,
            // "procedure"=>$procedureName,
            "random" => rand()
        );
        // 计算签名
        $original = http_build_query($arg_list);
        $signature = base64_encode(hash_hmac('SHA1', $original, $this->secretKey, true) . $original);
        return $signature;
    }

    /**
     * 删除分类
     * @param $cateGoryId
     * @return bool|mixed
     */
    public function delCategory($cateGoryId)
    {
        $this->initConfig();
        try {
            $errors = [
                "UnsupportedOperation.ClassNotEmpty	" => "不支持删除非空分类。",
                "FailedOperation.ClassNoFound" => "分类不存在",
                "InvalidParameterValue.ClassId" => "参数值错误,分类id",
                "InternalError" => "内部错误。",
                "UnauthorizedOperation" => "未授权操作。"
            ];

            $cred = new Credential($this->secretId, $this->secretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("vod.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, $this->region, $clientProfile);

            $req = new DeleteClassRequest();

            $params = "{\"ClassId\":{$cateGoryId}}";
            $req->fromJsonString($params);

            $resp = $client->DeleteClass($req);
            $jsonStr = $resp->toJsonString();
            $result =  json_decode($jsonStr, true);
            return $result;
        } catch (TencentCloudSDKException $e) {
            return $this->formatError($e->getErrorCode(), $errors, $e->getMessage());
        }
    }

    /**
     * 使用任务流模板进行视频处理
     *
     * @param [type] $videoId  视频id
     * @return void
     */
    public function processMediaByProcedureRequest($videoId)
    {
        $this->initConfig();
        $procedureName = $this->getOption("app.tengxunyun.vod.procedure");
        if (!$procedureName) return true;

        try {
            $errors = [
                "InvalidParameterValue.FileId" => "FileId 不存在。",
                "InvalidParameterValue.ProcedureName" => "任务模板名无效",
                "InvalidParameterValue.ClassId" => "参数值错误,分类id",
                "InternalError" => "内部错误。",
                "UnauthorizedOperation" => "未授权操作。"
            ];

            $cred = new Credential($this->secretId, $this->secretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("vod.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, $this->region, $clientProfile);


            $req = new ProcessMediaByProcedureRequest();

            $params = array(
                "FileId" => $videoId,
                "ProcedureName" => $procedureName
            );
            $req->fromJsonString(json_encode($params));
            $resp = $client->ProcessMediaByProcedure($req);
            $jsonStr = $resp->toJsonString();
            $result =  json_decode($jsonStr, true);
            return $result;
        } catch (TencentCloudSDKException $e) {
            return $this->formatError($e->getErrorCode(), $errors, $e->getMessage());
        }
    }

    /**
     * 修改视频信息
     * @param $videoId
     * @param $videoName
     * @param $coverImgData
     * @return false|mixed
     */
    public function ModifyMediaInfo($videoId, $coverImgData, $videoName=null){
        $this->initConfig();

        try {
            $errors = [
                "FailedOperation"=>"操作失败。",
                "FailedOperation.InvalidVodUser"=>"没有开通点播业务。",
                "InternalError.GetFileInfoError"=>"内部错误：获取媒体文件信息错误。",
                "InternalError.UpdateMediaError"=>"内部错误：更新媒体文件信息错误。",
                "InternalError.UploadCoverImageError"=>"内部错误：上传封面图片错误。",
                "InvalidParameterValue.AddKeyFrameDescsAndClearKeyFrameDescsConflict"=>"参数值错误：AddKeyFrameDescs 与 ClearKeyFrameDescs 参数冲突。",
                "InvalidParameterValue.AddKeyFrameDescsAndDeleteKeyFrameDescsConflict"=>"参数值错误：AddKeyFrameDescs 与 DeleteKeyFrameDescs 参数冲突。",
                "InvalidParameterValue.AddTagsAndClearTagsConflict"=>"参数值错误：AddTags 与 ClearTags 参数冲突。",
                "InvalidParameterValue.AddTagsAndDeleteTagsConflict"=>"参数值错误：AddTags 与 DeleteTags 参数冲突。",
                "InvalidParameterValue.Description"=>"参数值错误：Description 超过长度限制。",
                "InvalidParameterValue.ExpireTime"=>"参数值错误：ExpireTime 格式错误。",
                "InvalidParameterValue.ImageDecodeError"=>"图片解 Base64 编码失败。",
                "InvalidParameterValue.KeyFrameDescContentTooLong"=>"参数值错误：打点信息内容过长。",
                "InvalidParameterValue.Name"=>"参数值错误：Name 超过长度限制。",
                "InvalidParameterValue.TagTooLong"=>"参数值错误：标签过长。",
                "LimitExceeded.KeyFrameDescCountReachMax"=>"超过限制值：新旧打点信息个数和超过限制值。",
                "LimitExceeded.TagCountReachMax"=>"超过限制值：新旧标签个数和超过限制值。",
                "ResourceNotFound.FileNotExist"=>"资源不存在：文件不存在。",
                "UnauthorizedOperation"=>"未授权操作。"
            ];

            $cred = new Credential($this->secretId, $this->secretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("vod.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, $this->region, $clientProfile);

            $req = new ModifyMediaInfoRequest();

            $params = array(
                "FileId" => $videoId
            );

            if($videoName){
                $params["Name"] = $videoName;
            }

            if($coverImgData){
                $params["CoverData"] = $coverImgData;
            }

            $req->fromJsonString(json_encode($params));

            $resp = $client->ModifyMediaInfo($req);

            $jsonStr = $resp->toJsonString();
            $result =  json_decode($jsonStr, true);
            return $result;
        } catch (TencentCloudSDKException $e) {
            return $this->formatError($e->getErrorCode(), $errors, $e->getMessage());
        }
    }

    /**
     * 修改分类
     * @param $cateGoryId
     * @param $name
     * @return bool|mixed
     */
    public function modifyCategory($cateGoryId, $name)
    {
        $this->initConfig();
        try {

            $errors = [
                "FailedOperation.ClassNameDuplicate" => "分类名称重复",
                "FailedOperation.ClassNoFound" => "分类不存在",
                "InvalidParameterValue.ClassId" => "参数值错误,分类id",
                "InvalidParameterValue.ClassName" => "参数值错误，分类名称",
                "InternalError" => "内部错误。",
                "UnauthorizedOperation" => "未授权操作。"
            ];

            $cred = new Credential($this->secretId, $this->secretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("vod.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, $this->region, $clientProfile);

            $req = new ModifyClassRequest();

            $params = "{\"ClassId\":{$cateGoryId},\"ClassName\":\"{$name}\"}";
            $req->fromJsonString($params);

            $resp = $client->ModifyClass($req);
            $jsonStr = $resp->toJsonString();
            $result =  json_decode($jsonStr, true);
            return $result;
        } catch (TencentCloudSDKException $e) {
            return $this->formatError($e->getErrorCode(), $errors, $e->getMessage());
        }
    }

    /**
     * 创建分类
     *
     * @param $name
     * @param int $parentId
     * @return bool
     */
    public function addCategory($name, $parentId = -1)
    {
        $this->initConfig();
        try {
            $errors = [
                "FailedOperation.ClassLevelLimitExceeded" => "操作失败：超过分类层数限制",
                "FailedOperation.ClassNameDuplicate" => "操作失败：分类名称重复",
                "FailedOperation.ParentIdNoFound" => "操作失败：父类 ID 不存在。",
                "FailedOperation.SubclassLimitExceeded" => "操作失败：子类数量超过限制。",
                "InternalError" => "内部错误。",
                "InvalidParameterValue.ClassName" => "参数值错误：ClassName 无效。",
                "InvalidParameterValue.ParentId" => "参数值错误：ParentId 无效。",
                "UnauthorizedOperation" => "未授权操作。"
            ];

            $cred = new Credential($this->secretId, $this->secretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("vod.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, $this->region, $clientProfile);

            $req = new CreateClassRequest();

            $params = "{\"ParentId\":{$parentId},\"ClassName\":\"{$name}\"}";
            $req->fromJsonString($params);

            $resp = $client->CreateClass($req);
            $jsonStr = $resp->toJsonString();
            $result =  json_decode($jsonStr, true);
            return $result['ClassId'];
        } catch (TencentCloudSDKException $e) {
            return $this->formatError($e->getErrorCode(), $errors, $e->getMessage());
        }
    }

    /**
     * 获取视频信息
     */
    public function describeMediaInfosRequest($fileIds){
        $this->initConfig();
        try {
            $errors = [
                "FailedOperation.InvalidVodUser"=>"没有开通点播业务。",
                "InternalError"=>"内部错误。",
                "InternalError.GetMediaListError"=>"内部错误：获取媒体列表错误。",
                "InvalidParameter"=>"参数错误。",
                "InvalidParameterValue.FileIds"=>"FileIds 参数错误。",
                "InvalidParameterValue.FileIdsEmpty"=>"FileIds 数组为空。",
                "UnauthorizedOperation"=>"未授权操作。"
            ];

            $cred = new Credential($this->secretId, $this->secretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("vod.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, $this->region, $clientProfile);

            $req = new DescribeMediaInfosRequest();
            $params = array(
                "FileIds" =>  $fileIds
            );
            $req->fromJsonString(json_encode($params));

            $resp = $client->DescribeMediaInfos($req);
            $jsonStr = $resp->toJsonString();
            $result =  json_decode($jsonStr, true);
            return isset($result['MediaInfoSet'])?$result['MediaInfoSet']:[];
        } catch (TencentCloudSDKException $e) {
            return $this->formatError($e->getErrorCode(), $errors, $e->getMessage());
        }
    }

    protected function formatError($code, $errors, $defaultErrorMsg = "未知错误")
    {
        $msg = isset($errors[$code]) ? $errors[$code] : $defaultErrorMsg;

        return $this->error()->add($msg);
    }
}
