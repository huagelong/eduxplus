<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/6/22 11:13
 */

namespace Eduxplus\CoreBundle\Lib\Service\Vod;


use Eduxplus\CoreBundle\Lib\Base\BaseService;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Vod\Vod;
use Eduxplus\CoreBundle\Lib\Service\CacheService;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;

/**
 * 视频点播->媒资管理配置->存储管理->管理->权限->读写权限:公共读
 * HLS: 视频点播->媒资处理配置->转码模版组->添加转码模板组 (HLS不要私有加密)
 * https://help.aliyun.com/document_detail/68612.html?spm=5176.12672711.0.0.ca0b49431tLENw  HLS标准加密 KMS先开通
 * 点播转码完成回调网址 app_glob_vodCallback
 *
 * Class AliyunVodService
 * @package Eduxplus\CoreBundle\Lib\Service\Vod
 */
class AliyunVodService extends BaseService
{
    const VOD_CLIENT_NAME = 'eduxplus';
    protected $accessKeyId;
    protected $accessKeySecret;
    protected $userId;
    protected $templateGroupId;
    protected $regionId;
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    protected function initConfig(){
        $this->accessKeyId = $this->getOption("app.aliyun.accesskeyId");
        $this->accessKeySecret = $this->getOption("app.aliyun.accesskeySecret");
        $this->userId = $this->getOption("app.aliyun.userId");
        $this->templateGroupId = $this->getOption("app.aliyun.vod.templateGroupId");
        $this->regionId = $this->getOption("app.aliyun.region");
    }

    public function getConfigUserId(){
        return $this->getOption("app.aliyun.userId");
    }

    public function getRegion(){
        return $this->getOption("app.aliyun.region");
    }

    /**
     * 初始化客户端
     *
     * @return static
     */
    protected  function initClient() {
        $this->initConfig();
        //https://help.aliyun.com/document_detail/98194.html?spm=a2c4g.11186623.6.612.1fb73ecbvK52qE
        $regionId =  $this->regionId;
        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
            ->regionId($regionId)
            ->connectTimeout(1)
            ->timeout(3)
            ->name(self::VOD_CLIENT_NAME);
    }

    /**
     * 提交媒体转码作业
     * @param $videoId
     * @param $cipherText
     * @return array|bool
     */
    public function submitTranscodeJobs($videoId, $callback){
        if(!$videoId) return true;
        $this->initClient();
        if(!$this->templateGroupId) return true;
        $dataKey = $this->generateDataKey();
        $options = [
            'query' => [
                'TemplateGroupId' => $this->templateGroupId,
                'VideoId' => $videoId,
                'RegionId' => $this->regionId,
            ],
        ];

        if($dataKey){
            list($cipherText, $plaintext) = $dataKey;
            //更新keyData
            //todo
            $callback($videoId, $dataKey);
            
            $payLoadArr = [];
            $payLoadArr['videoId'] = $videoId;
            $key = $this->getConfig("secret");
            $token = JWT::encode($payLoadArr, $key);
            $url = $this->getOption("app.domain");
            $url = trim($url, "/");
            $url = $url.$this->genUrl("app_glob_aliyunVodPlayCheck");
            $url = $url."?Ciphertext={$token}";
            $encryptConfig = [];
            $encryptConfig['CipherText'] =$cipherText;
            $encryptConfig['DecryptKeyUri'] = $url;
            $encryptConfig['KeyServiceType'] = 'KMS';
            $options['query']['EncryptConfig'] = json_encode($encryptConfig);
        }
//        var_dump($options);
        try {
            $result = AlibabaCloud::rpc()
                ->product('vod')
                ->scheme('https') // https | http
                ->version('2017-03-21')
                ->action('SubmitTranscodeJobs')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options($options)->request();
            $info = $result->toArray();
            return $info;
        } catch (\Exception $e){
            $errors = [
                "Throttling"=>"您这个时段的流量已经超限",
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }
    }

    /**
     * 解密
     * @param $ciphertextBlob
     * @param $encryptionContext
     * @return array|bool
     */
    public function decrypt($ciphertextBlob){
        try {
            $this->initClient();
            $result = AlibabaCloud::rpc()
                ->product('Kms')
                 ->scheme('https') // https | http
                ->version('2016-01-20')
                ->action('Decrypt')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options([
                    'query' => [
                        'RegionId' => $this->regionId,
                        'CiphertextBlob' => $ciphertextBlob
                    ],
                ])
                ->request();
            $uploadInfo  = $result->toArray();
            return $uploadInfo;
        }catch (\Exception $e){
            $errors = [
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }
    }

    /**
     * 生成数据密钥
     */
    public function generateDataKey(){
        $cacheKey = "generateDataKey";
        $data = $this->cacheService->get($cacheKey);
        if($data) return $data;
        $this->initClient();
        $kmsKeyId = $this->getOption("app.aliyun.vod.kms.keyId");
        try {
            $result = AlibabaCloud::rpc()
                ->product('Kms')
                ->scheme('https') // https | http
                ->version('2016-01-20')
                ->regionId($this->regionId)
                ->action('GenerateDataKey')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options([
                    'query' => [
                        'KeyId' => $kmsKeyId,
                        'KeySpec' => "AES_128",
                    ],
                ])->request();
            $info = $result->toArray();
            $rs = [$info['CiphertextBlob'], $info['Plaintext']];
            if($rs) $this->cacheService->set($cacheKey, $rs, 3600*24);
            return $rs;
        } catch (\Exception $e){
            $errors = [
                "Throttling"=>"您这个时段的流量已经超限",
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }
    }

    /**
     * 检查回调签名
     *
     * @param Request $request
     * @param $url
     * @param $privateKey
     * @return bool
     */
    public function callBackSignatureCheck($url){
        $privateKey = $this->getOption("app.aliyun.vod.callbackSignPrivateKey");
        if(!$privateKey) return true;
        $request = $this->request();
        $timeStamp = $request->headers->get('X-VOD-TIMESTAMP');
        $signature = $request->headers->get('X-VOD-SIGNATURE');
        $md5Content = $url."|".$timeStamp."|".$privateKey;
        $realSign = md5($md5Content);
        return $signature == $realSign;
    }

    /**
     * 获取视频上传地址和凭证
     * @return bool
     */
    public function createUploadVideo($title, $fileName,$cateId=0, $coverUrl='', $descr='') {
        try {
            $this->initClient();
            $result = AlibabaCloud::rpc()
                ->product('vod')
                 ->scheme('https')
                ->version('2017-03-21')
                ->action('CreateUploadVideo')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options([
                    'query' => [
                        'RegionId' => $this->regionId,
                        'Title' => $title,
                        'FileName' => $fileName,
                        'CoverURL' => $coverUrl,
                        'Description' =>$descr,
                        'CateId' => $cateId,
//                        'TemplateGroupId' => $this->templateGroupId,
                    ],
                ])
                ->request();
            $uploadInfo  = $result->toArray();
            return $uploadInfo;
        }catch (\Exception $e){
            $errors = [
                "InvalidFileName.Extension"=>"点播支持的文件扩展名无效",
                "IllegalCharacters"=> "参数值中不能包含表情符",
                "LengthExceededMax"=> "参数值长度超过MaxLength限制",
                "TagsExceededMax"=> "设置的标签个数超过最多16个的限制。",
                "InvalidTemplateGroupId.NotFound"=> "指定的模板组ID不存在。",
                "InvalidStorage.NotFound"=> "设置的存储地址不存在",
                "Forbidden.InitFailed"=> "服务开通时账号初始化失败",
                "AddVideoFailed"=> "创建视频信息失败，请稍后重试。"
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }

    }



    public function refreshUploadVideo($videoId){
        try {
            $this->initClient();
            $result = AlibabaCloud::rpc()
                ->product('vod')
                 ->scheme('https')
                ->version('2017-03-21')
                ->action('RefreshUploadVideo')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options([
                    'query' => [
                        'RegionId' => $this->regionId,
                        'VideoId' =>$videoId,
                    ],
                ])
                ->request();
            $uploadInfo = $result->toArray();
//            var_dump($uploadInfo);
            return $uploadInfo;
        }catch (\Exception $e){
            $errors = [
                "InvalidFileName.Extension"=>"点播支持的文件扩展名无效",
                "IllegalCharacters"=> "参数值中不能包含表情符",
                "LengthExceededMax"=> "参数值长度超过MaxLength限制",
                "TagsExceededMax"=> "设置的标签个数超过最多16个的限制。",
                "InvalidTemplateGroupId.NotFound"=> "指定的模板组ID不存在。",
                "InvalidStorage.NotFound"=> "设置的存储地址不存在",
                "Forbidden.InitFailed"=> "服务开通时账号初始化失败",
                "AddVideoFailed"=> "创建视频信息失败，请稍后重试。"
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }
    }

    public function delCategory($cateId){
        try {
            $this->initClient();
            $result = AlibabaCloud::rpc()
                ->product('vod')
                 ->scheme('https') // https | http
                ->version('2017-03-21')
                ->action('DeleteCategory')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options([
                    'query' => [
                        'RegionId' => $this->regionId,
                        'CateId' => $cateId,
                    ],
                ])
                ->request();
            return $result->toArray();
        }catch (\Exception $e){
            $errors = [
                "InvalidCateId.NotFound	"=> "父分类ID不存在。"
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }
    }


    public function updateCategory($cateId, $name){
        try {
            $this->initClient();
            $result = AlibabaCloud::rpc()
                ->product('vod')
                 ->scheme('https') // https | http
                ->version('2017-03-21')
                ->action('UpdateCategory')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options([
                    'query' => [
                        'RegionId' =>$this->regionId,
                        'CateId' =>$cateId,
                        'CateName' => $name,
                    ],
                ])
                ->request();

            return $result->toArray();
        }catch (\Exception $e){
            $errors = [
                "InvalidCateId.NotFound	"=> "父分类ID不存在。"
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }
    }


    /**
     * 添加分类
     *
     * @param $name
     * @param int $parentCateId
     * @return $this|array|bool
     */
    public function addCategory($name, $parentCateId=0){
        try {
            $this->initClient();
            $result = AlibabaCloud::rpc()
                ->product('vod')
                 ->scheme('https') // https | http
                ->version('2017-03-21')
                ->action('AddCategory')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options([
                    'query' => [
                        'RegionId' => $this->regionId,
                        'CateName' => $name,
                        'ParentId' => $parentCateId,
                    ],
                ])
                ->request();

            return $result->toArray();
        }catch (\Exception $e){
            $errors = [
                "LevelExceededMax"=>"分类层级超过最大限制。",
                "SubTotalExceededMax"=> "子分类个数超过最大限制。",
                "InvalidCateId.NotFound	"=> "父分类ID不存在。"
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }
    }

    /**
     * 获取播放凭证
     */
    public function getVideoPlayAuth($videoId){
        try {
            $this->initClient();
            $result = AlibabaCloud::rpc()
                ->product('vod')
                 ->scheme('https') // https | http
                ->version('2017-03-21')
                ->action('GetVideoPlayAuth')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options([
                    'query' => [
                        'RegionId' => $this->regionId,
                        'VideoId' => $videoId,
                    ],
                ])
                ->request();

            return $result->toArray();
        }catch (\Exception $e){
            $errors = [
                "Forbidden.IllegalStatus"=>"视频状态无效。",
                "InvalidVideo.NotFound"=> "视频不存在。",
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }
    }

    /**
     * 获取播放凭证
     */
    public function getVideoPlayInfo($videoId){
        try {
            $this->initClient();

            $result = AlibabaCloud::rpc()
                ->product('vod')
                 ->scheme('https') // https | http
                ->version('2017-03-21')
                ->action('GetPlayInfo')
                ->method('POST')
                ->client(self::VOD_CLIENT_NAME)
                ->options([
                    'query' => [
                        'RegionId' => $this->regionId,
                        'VideoId' => $videoId,
                    ],
                ])
                ->request();

            return $result->toArray();
        }catch (\Exception $e){
            $errors = [
                "Forbidden.IllegalStatus"=>"视频状态无效。",
                "InvalidVideo.NotFound"=> "视频不存在。",
                "InvalidVideo.NoneStream"=>"根据您的筛选条件找不到可以播放的转码输出流",
                "Forbidden.AliyunVoDEncryption"=>"当前仅存在阿里云视频加密的转码输出流",
            ];
            $errInfo =  $this->formatError($e->getMessage(), $errors, $e->getMessage());
            return $errInfo;
        }
    }


    /**
     * 获取播放信息
     *
     * @param $videoId
     * @return array|mixed
     */
    public function getVodPlayInfo($videoId){
        $rs = $this->getVideoPlayAuth($videoId);
        // $playInfo = $this->getVideoPlayInfo($videoId);
        if($this->error()->has()){
            return $this->error()->getLast();
        }
        // var_dump($playInfo);exit;
        // $playList = [];
        // // $key = $this->getConfig("secret");
        // // $mtsHlsUriToken = md5($key.$videoId);
        // if(isset($playInfo['PlayInfoList']['PlayInfo']) && $playInfo['PlayInfoList']['PlayInfo']){
        //     foreach ($playInfo['PlayInfoList']['PlayInfo'] as $v){
        //         // $playList[$v['Definition']] = $v['PlayURL']."&MtsHlsUriToken=".$mtsHlsUriToken;
        //         $playList[$v['Definition']] = $v['PlayURL'];
        //     }
        // }

        $data = [];
        $data['playAuth'] = $rs['PlayAuth'];
        // $data['source'] = $playList?json_encode($playList, JSON_UNESCAPED_UNICODE):"";
        return $data;
    }

    /**
     * 错误格式化
     *
     * @param $code
     * @param $errors
     * @return bool
     */
    protected function formatError($code, $errors, $defaultErrorMsg="未知错误"){
        $cerrors = [
            "OperationDenied.Suspended"=>"账号已欠费，请充值。",
            "OperationDenied"=>"账号未开通视频点播服务。",
            "Forbidden"=>"无权限执行该操作。",
            "InternalError"=>"后台错误",
            "ServiceUnAvailable"=>"服务不可用",
            "MissingParameter"=>"缺少参数",
            "InvalidParameter"=>"参数无效",
        ];
        $errors = array_merge($cerrors, $errors);

        $codeArr = explode(":", $code);
        $codeKey = current($codeArr);
        $msg = isset($errors[$codeKey])?$errors[$codeKey]:$defaultErrorMsg;
//        var_dump($msg);
        return $this->error()->add($msg);
    }

}
