<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 15:41
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Service\Teach\ProductService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use App\Bundle\AppBundle\Lib\Base\BaseController;
use App\Bundle\AppBundle\Lib\Service\UploadService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class GlobController extends BaseAdminController
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
            $initialPreview= [];
            $initialPreviewConfig = [];
            foreach ($file as $v){
                $size = $v->getFileInfo()->getSize();
                $filePath = $uploadService->upload($v, $type);
                $miniType = $v->getClientMimeType();
                $originalName = $v->getClientOriginalName();
                if(stristr($miniType, "image")){
                    $initialPreview[] = $filePath;
                    $tmp=[];
                    $tmp['type'] = 'image';
                    $tmp['caption'] = $originalName;
                    $tmp['size'] =  $size;
                    $initialPreviewConfig[] = $tmp;
                }else{
                    $initialPreview[] = $filePath;
                    $tmp=[];
                    $tmp['type'] = 'other';
                    $tmp['caption'] = $originalName;
                    $tmp['size'] =  $size;
                    $initialPreviewConfig[] = $tmp;
                }
                $filePaths[] = $filePath;
            }
            $data = [];
            $data["initialPreview"] = $initialPreview;
            $data['initialPreviewConfig'] = $initialPreviewConfig;
            $data['append'] = true;
            return $this->json($data);
        }catch (\Exception $e){
            return $this->json(['error'=>$e->getMessage()]);
        }
    }

    /**
     * @Rest\Get("/api/glob/searchUserDo", name="admin_api_glob_searchUserDo")
     */
    public function searchUserDoAction(Request $request, ProductService $productService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $productService->searchAdminFullName($kw);
        return $data;
    }

    /**
     * @Rest\Get("/api/glob/searchProductDo", name="admin_api_glob_searchProductDo")
     */
    public function searchProductDoAction(Request $request, ProductService $productService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $productService->searchProductName($kw);
        return $data;
    }I


}
