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
        $targetDirRoot = $this->getParameter("upload_dir");
        $pathTmp = "/upload/".$type."/".date('Y')."/".date('m')."/".date('d')."/";
        $targetDir = $targetDirRoot.$pathTmp;
        $fileName = $file->getClientOriginalName().".".md5(uniqid()).'.'.$file->guessExtension();
        $file->move($targetDir, $fileName);
        $path = $pathTmp.$fileName;

        $uploadAdapter = (int) $this->getOption("app.upload.adapter");
        $uploadAdapter = $uploadAdapter?$uploadAdapter:1;
        if($uploadAdapter == 1) return $path;
        if($uploadAdapter == 2){
            $remoteFilePath = $type."/".date('Y/m/d')."/".$fileName;
            return $this->aliyunOssService->upOss($remoteFilePath, $path);
        }
    }
}
