<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 15:49
 */

namespace App\Bundle\AppBundle\Lib\Service;

use App\Bundle\AppBundle\Lib\Base\BaseService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService extends BaseService
{

    public function upload(UploadedFile $file, $type)
    {
        $targetDirRoot = $this->getParameter("upload_dir");
        $pathTmp = "/upload/".$type."/".date('Y')."/".date('m')."/".date('d')."/";
        $targetDir = $targetDirRoot.$pathTmp;
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($targetDir, $fileName);
        $path = $pathTmp.$fileName;
        return $path;
    }
}
