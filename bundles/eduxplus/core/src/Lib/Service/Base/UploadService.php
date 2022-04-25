<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 15:49
 */

namespace Eduxplus\CoreBundle\Lib\Service\Base;

use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Lib\Service\Base\File\AliyunOssService;
use Eduxplus\CoreBundle\Lib\Service\Base\File\TengxunyunCosService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService extends BaseService
{
    protected $aliyunOssService;
    protected $tengxunyunCosService;

    public function __construct(AliyunOssService $aliyunOssService, TengxunyunCosService $tengxunyunCosService)
    {
        $this->aliyunOssService = $aliyunOssService;
        $this->tengxunyunCosService = $tengxunyunCosService;
    }

    public function up($remoteFilePath, $localFile){

        $uploadAdapter = (int) $this->getOption("app.upload.adapter");
        $uploadAdapter = $uploadAdapter?$uploadAdapter:1;
        //本地
        if($uploadAdapter == 1){
            rename($remoteFilePath, $localFile);
            return $remoteFilePath;
        }
        //阿里云oss
        if($uploadAdapter == 2){
            return $this->aliyunOssService->up($remoteFilePath, $localFile);
        }

        //腾讯云cos
        if($uploadAdapter == 3){
            return $this->tengxunyunCosService->up($remoteFilePath, $localFile);
        }
    }


    public function upload(UploadedFile $file, $type)
    {
//        $file->getFileInfo()->getSize();
//        $file->getFileInfo()->getType();
//        $file->getFileInfo()->getExtension();
//        $file->getClientMimeType()
        // $targetDirRoot = $this->getParameter("upload_dir");
        $targetDirRoot = $this->getBasePath()."/var/tmp";
        $uploadAdapter = (int) $this->getOption("app.upload.adapter");
        $uploadAdapter = $uploadAdapter?$uploadAdapter:1;

        $pathTmp =  "/upload/".$type."/".date('Y')."/".date('m')."/".date('d')."/";
        $domain = "";
        if( $uploadAdapter == 1){
            $targetDir = $this->getBasePath()."/public".$pathTmp;
        }else{
            if($domain == 2){
                $domain = $this->getOption("app.aliyun.cdn.domain");
            }
            if($domain == 3){
                $domain = $this->getOption("app.tengxunyun.cdn.domain");
            }
            $targetDir = $targetDirRoot.$pathTmp;
        }

        if(!is_dir($targetDir)){
            mkdir($targetDir, 0777, true);
        }

        $fileName = $file->getClientOriginalName().".".md5(uniqid()).'.'.$file->guessExtension();
        $file->move($targetDir, $fileName);
        $path = $pathTmp.$fileName;

        if($uploadAdapter == 2){
            $remoteFilePath = $type."/".date('Y/m/d')."/".$fileName;
            $localFile = $targetDir.$fileName;
            return $this->aliyunOssService->up($remoteFilePath, $localFile);
        }

        if($uploadAdapter == 3){
            $remoteFilePath = $type."/".date('Y/m/d')."/".$fileName;
            $localFile = $targetDir.$fileName;
            return $this->tengxunyunCosService->up($remoteFilePath, $localFile);
        }

        return $domain.$path;
    }
}
