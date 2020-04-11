<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 15:41
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AppBundle\Lib\Base\BaseController;
use App\Bundle\AppBundle\Lib\Service\UploadService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class GlobController extends BaseController
{
    /**
     * @Rest\Post("/glob/upload/{type}", name="admin_glob_upload")
     */
    public function uploadAction($type = "img", Request $request, UploadService $uploadService){
//        $exists = $request->get("exists");
//        dump($exists);exit;
        $file = $request->files->all();
        if(!$file){
            return $this->json(['error'=>"没有文件上传!"]);
        }
        try{
            $filePaths = [];
            foreach ($file as $v){
                $filePath = $uploadService->upload($v, $type);
                $filePaths[] = $filePath;
            }
            $data = [];
            $data["initialPreview"] = $filePaths;
            $data['append'] = true;
            return $this->json($data);
        }catch (\Exception $e){
            return $this->json(['error'=>$e->getMessage()]);
        }
    }
}
