<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2018/1/4
 * Time: 11:51
 */

namespace Lib\Support\File\AliOss;

use Lib\Support\File\FileAbstract;
use Lib\Support\Xtrait\Helper;

use OSS\OssClient;
use OSS\Core\OssException;

class Oss extends FileAbstract
{
    use Helper;

    protected $ossReturn;

    /**
     * 删除文件
     *
     * @param $uri
     */
    public function delete($uri)
    {
        $ossClient = $this->getOssClient();
//        $bucket =  $this->config()->get("app.oss.bucket");
        $bucket = $this->getDbOption("aliyun", "oss_bucket");
        $key = $this->getObj($uri);

        $result = $ossClient->deleteObject($bucket, $key);
        return isset($result['oss-request-url'])?$result['oss-request-url']:false;
    }


    public function getSignedUrl($uri, $timeout=10)
    {
        $ossClient = $this->getOssClient();
//        $bucket =  $this->config()->get("app.oss.bucket");
        $bucket = $this->getDbOption("aliyun", "oss_bucket");
        $object = $this->getObj($uri);
        $signedUrl = $ossClient->signUrl($bucket, $object, $timeout);
        return $signedUrl;
    }


    public function getObj($uri)
    {
        if(stristr($uri, "http")){
//            $temp = $this->config()->get("app.oss.endpoint");
            $temp = $this->getDbOption("aliyun", "oss_endpoint");
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
    public function save($remotePath,$localPath)
    {
        $remotePath = trim($remotePath, '/');
//        $bucket =  $this->config()->get("app.oss.bucket");
        $bucket = $this->getDbOption("aliyun", "oss_bucket");
        $ossClient = $this->getOssClient();
        $result = $ossClient->uploadFile($bucket, $remotePath, $localPath);
        $this->ossReturn = $result;
        return isset($result['oss-request-url'])?$result['oss-request-url']:"";
    }

    public function getReturn()
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
//        $accessKeyID = $this->config()->get("app.oss.accessKeyID");
        $accessKeyID = $this->getDbOption("aliyun", "oss_accessKeyID");
//        $accessKeySecret = $this->config()->get("app.oss.accessKeySecret");
        $accessKeySecret = $this->getDbOption("aliyun", "oss_accessKeySecret");
//        $endpoint = $this->config()->get("app.oss.endpoint");
        $endpoint = $this->getDbOption("aliyun", "oss_endpoint");
//        $useCname = $this->config()->get("app.oss.useCname");
        $useCname = $this->getDbOption("aliyun", "oss_useCname");

//        $timeout = $this->config()->get("app.oss.timeout");
        $timeout = $this->getDbOption("aliyun", "oss_timeout");

//        $connectTimeout = $this->config()->get("app.oss.ConnectTimeout");
        $connectTimeout = $this->getDbOption("aliyun", "oss_ConnectTimeout");

        try {
            $ossClient = new OssClient($accessKeyID, $accessKeySecret, $endpoint, $useCname);
            if($timeout) $ossClient->setTimeout($timeout);
            if($connectTimeout) $ossClient->setConnectTimeout($connectTimeout);

            return $ossClient;
        } catch (OssException $e) {
            throw new \Exception($e->getMessage() . "\n");
        }
    }
}