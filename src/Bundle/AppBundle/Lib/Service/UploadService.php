<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 15:49
 */

namespace App\Bundle\AppBundle\Lib\Service;

use App\Bundle\AppBundle\Lib\Base\BaseService;
use App\Bundle\AppBundle\Lib\Service\File\AliyunOssService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService extends BaseService
{
    protected $aliyunOssService;

    public function __construct(AliyunOssService $aliyunOssService)
    {
        $this->aliyunOssService = $aliyunOssService;
    }

    public function upload(UploadedFile $file, $type)
    {
//        $file->getFileInfo()->getSize();
        // $targetDirRoot = $this->getParameter("upload_dir");
        $targetDirRoot = $this->getBasePath()."/var/tmp";
        $uploadAdapter = (int) $this->getOption("app.upload.adapter");
        $uploadAdapter = $uploadAdapter?$uploadAdapter:1;

        $pathTmp = "/upload/".$type."/".date('Y')."/".date('m')."/".date('d')."/";
        if( $uploadAdapter == 1){
            $targetDir = $pathTmp;
        }else{
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
            return $this->aliyunOssService->upOss($remoteFilePath, $targetDir);
        }

        return $path;
    }
}
