<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/9/17 15:51
 */

namespace Eduxplus\CoreBundle\Lib\Service\File;


use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Qcloud\Cos\Client;

class TengxunyunCosService extends BaseService
{

    protected function getClient(){
        $secretId = $this->getOption("app.tengxunyun.secretId"); //"云 API 密钥 SecretId";
        $secretKey =  $this->getOption("app.tengxunyun.secretKey"); //"云 API 密钥 SecretKey";
        $region = $this->getOption("app.tengxunyun.region"); //设置一个默认的存储桶地域
        $cosClient = new Client(
            array(
                'region' => $region,
                'schema' => 'https', //协议头部，默认为http
                'credentials'=> array(
                    'secretId'  => $secretId ,
                    'secretKey' => $secretKey)
            ));
        return $cosClient;
    }

    /**
     * 上传
     * @param $remotePath
     * @param $localPath
     */
    public function up($remotePath, $localPath){
        try {
            $bucket = $this->getOption("app.tengxunyun.bucket"); //存储桶名称 格式：BucketName-APPID

            $key = $remotePath;
            $srcPath = $localPath;//本地文件绝对路径
            $file = fopen($srcPath, "rb");
            if ($file) {
                $result = $this->getClient()->putObject(
                    array(
                    'Bucket' => $bucket,
                    'Key' => $key,
                    'Body' => $file
                    )
                );
                $result = $result->toArray();
                return "http://".$result['Location'];
            }
        } catch (\Exception $e) {
            return $this->error()->add($e->getMessage());
        }
    }
}
