<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 15:41
 */

namespace App\Bundle\AdminBundle\Controller;


use App\Bundle\AdminBundle\Service\Mall\GoodsService;
use App\Bundle\AdminBundle\Service\Teach\ProductService;
use App\Bundle\AdminBundle\Service\Teach\StudyPlanService;
use App\Bundle\AppBundle\Lib\Base\BaseAdminController;
use App\Bundle\AppBundle\Lib\Base\BaseController;
use App\Bundle\AppBundle\Lib\Service\UploadService;
use App\Bundle\AppBundle\Lib\Service\Vod\AliyunVodService;
use App\Bundle\AppBundle\Lib\Service\Vod\TengxunyunVodService;
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
     * @Rest\Get("/glob/searchAdminUser/do", name="admin_api_glob_searchAdminUserDo")
     */
    public function searchAdminUserDoAction(Request $request, ProductService $productService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $productService->searchAdminFullName($kw);
        return $data;
    }

    /**
     * @Rest\Get("/glob/searchUser/do", name="admin_api_glob_searchUserDo")
     */
    public function searchUserDoAction(Request $request, ProductService $productService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $productService->searchFullName($kw);
        return $data;
    }

    /**
     * @Rest\Get("/glob/searchProduct/do", name="admin_api_glob_searchProductDo")
     */
    public function searchProductDoAction(Request $request, ProductService $productService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $productService->searchProductName($kw);
        return $data;
    }

    /**
     * @Rest\Get("/glob/searchGoods/do", name="admin_api_glob_searchGoodsDo")
     */
    public function searchGoodsDoAction(Request $request, GoodsService $goodsService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $goodsService->searchGoodsName($kw);
        return $data;
    }

    /**
     * @Rest\Get("/glob/searchCourse/do", name="admin_api_glob_searchCourseDo")
     */
    public function searchCourseDoAction(Request $request, StudyPlanService $studyPlanService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $studyPlanService->searchCourseName($kw);
        return $data;
    }


    /**
     * 腾讯云 高级播放签名生成
     * @Rest\Get("/glob/tengxunyunVodAndvancePlaySign/do", name="admin_api_glob_tengxunyunVodAndvancePlaySignDo")
     */
    public function tengxunyunVodAndvancePlaySignAction(Request $request, TengxunyunVodService $tengxunyunVodService){
        $videoId = $request->get("videoId");
        $rs = $tengxunyunVodService->getAndvancePlaySign($videoId);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseSuccess($rs);
    }

    /**
     * 腾讯云 获取播放地址
     * @Rest\Get("/glob/tengxunyunVodEncryptionPlayUrl/do", name="admin_api_glob_tengxunyunVodEncryptionPlayUrlDo")
     */
    public function tengxunyunVodEncryptionPlayUrlAction(Request $request, TengxunyunVodService $tengxunyunVodService){
        $vodeoId = $request->get("vodeoId");
        $rs = $tengxunyunVodService->getVodEncryptionPlayUrl($vodeoId);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseSuccess($rs);
    }

    /**
     * 腾讯云 上传签名
     * @Rest\Get("/glob/tengxunyunSignature/do", name="admin_api_glob_tengxunyunSignatureDo")
     */
    public function tengxunyunSignatureAction(Request $request, TengxunyunVodService $tengxunyunVodService){
        $rs = $tengxunyunVodService->getUploadSignature();
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseSuccess($rs);
    }


    /**
     * 阿里云点播信息 playAuth 播放凭证,播放列表等
     * @Rest\Get("/glob/getAliyunVodPlayInfo/do", name="admin_api_glob_getAliyunVodPlayInfoDo")
     */
    public function getAliyunVodPlayInfoAction(Request $request, AliyunVodService $aliyunVodService){
        $videoId = $request->get("videoId");
        $data = $aliyunVodService->getVodPlayInfo($videoId);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseSuccess($data);
    }

    /**
     * 阿里云 生成上传凭证
     * @Rest\Get("/glob/aliyunVodCreateUploadVideo/do", name="admin_api_glob_aliyunVodCreateUploadVideoDo")
     */
    public function aliyunVodCreateUploadVideoAction(Request $request, AliyunVodService $aliyunVodService){
        $title = $request->get("title");
        $fileName = $request->get("fileName");
        $cateId = $request->get("cateId", 0);
        if(!$title) return $this->responseError("title 不能为空!");
        if(!$fileName) return $this->responseError("fileName 不能为空!");

        $rs = $aliyunVodService->createUploadVideo($title, $fileName, $cateId);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseSuccess($rs);
    }

    /**
     * 阿里云 刷新上传凭证
     *
     * @Rest\Get("/glob/aliyunVodRefreshUploadVideo/do", name="admin_api_glob_aliyunVodRefreshUploadVideoDo")
     */
    public function aliyunVodRefreshUploadVideoAction(Request $request, AliyunVodService $aliyunVodService){
        $videoId = $request->get("videoId");
        if(!$videoId) return $this->responseError("videoId 不能为空!");
        $rs = $aliyunVodService->refreshUploadVideo($videoId);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseSuccess($rs);
    }

}
