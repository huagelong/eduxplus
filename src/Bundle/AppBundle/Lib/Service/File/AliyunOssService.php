<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/15 11:47
 */

namespace App\Bundle\AppBundle\Lib\Service\File;

use App\Bundle\AppBundle\Lib\Base\BaseService;
use OSS\Core\OssException;
use OSS\OssClient;

class AliyunOssService extends BaseService{

    protected $bucket;

    protected $endpoint;

    protected $ossReturn;
    

    /**
     * 删除文件
     *
     * @param $uri
     */
    public function deleteOssObject($uri)
    {
        $ossClient = $this->getOssClient();
        $bucket = $this->bucket;
        $key = $this->getOssObj($uri);

        $result = $ossClient->deleteObject($bucket, $key);
        return isset($result['oss-request-url'])?$result['oss-request-url']:false;
    }

    /**
     * 有时间限制的文件地址
     *
     * @param [type] $uri
     * @param integer $timeout 过期时间 （秒）
     * @return void
     */
    public function getOssSignedUrl($uri, $timeout=10)
    {
        $ossClient = $this->getOssClient();
        $bucket = $this->bucket;
        $object = $this->getOssObj($uri);
        $signedUrl = $ossClient->signUrl($bucket, $object, $timeout);
        return $signedUrl;
    }


    public function getOssObj($uri)
    {
        if(stristr($uri, "http")){
            $temp = $this->endpoint;
            $temp = ltrim($temp,"/")."/";
            $key = substr($uri,strlen($temp));
        }else{
            return ltrim($uri,"/");
        }
        return ltrim($key,"/");
    }

    /**
     * 上传图片到阿里云oss
     *
     * @param $remotePath
     * @param $localPath
     * @return string
     */
    public function upOss($remotePath, $localPath)
    {
//        print_r(func_get_args());
        $remotePath = trim($remotePath, '/');
        $ossClient = $this->getOssClient();

        $result = $ossClient->uploadFile($this->bucket, $remotePath, $localPath);
        $this->ossReturn = $result;
        return isset($result['oss-request-url'])?$result['oss-request-url']:"";
    }

    public function getOssReturn()
    {
        return $this->ossReturn;
    }

    /**
     * 获取oss对象
     *
     * @return OssClient
     * @throws \Exception
     */
    protected function getOssClient()
    {
        $accessKeyID = $this->getOption("app.aliyun.accesskeyId");
        $accessKeySecret = $this->getOption("app.aliyun.accesskeySecret");
        $this->bucket = $this->getOption("app.aliyun.oss.bucket");
        $this->endpoint = $this->getOption("app.aliyun.oss.endpoint");
        $useCname = false;

        try {
            $ossClient = new OssClient($accessKeyID, $accessKeySecret, $this->endpoint, $useCname);
            return $ossClient;
        } catch (OssException $e) {
            throw new \Exception("error:".$e->getMessage() . "\n");
        }
    }

}
