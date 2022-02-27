<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/10 15:41
 */

namespace Eduxplus\CoreBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Eduxplus\CoreBundle\Lib\Service\Base\UploadService;
use Eduxplus\CoreBundle\Lib\Service\Base\Vod\AliyunVodService;
use Eduxplus\CoreBundle\Lib\Service\Base\Vod\TengxunyunVodService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\CoreBundle\Service\UserService;

class GlobController extends BaseAdminController
{
    
    public function uploadAction($type, Request $request, UploadService $uploadService){
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
            $this->adminBaseService->errorLog($e->getTraceAsString());
            return $this->json(['error'=>$e->getMessage()]);
        }
    }

    
    public function searchAdminUserDoAction(Request $request, UserService $userService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $userService->searchAdminFullName($kw);
        return $this->responseSuccess($data);
    }

    
    public function searchUserDoAction(Request $request, UserService $userService){
        $kw = $request->get("kw");
        if(!$kw) return [];
        $data = $userService->searchFullNameFormat($kw);
        return $this->responseSuccess($data);
    }



    
    public function tengxunyunVodAndvancePlaySignAction(Request $request, TengxunyunVodService $tengxunyunVodService){
        $videoId = $request->get("videoId");
        $rs = $tengxunyunVodService->getAndvancePlaySign($videoId);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseSuccess($rs);
    }

    
    public function tengxunyunVodEncryptionPlayUrlAction(Request $request, TengxunyunVodService $tengxunyunVodService){
        $vodeoId = $request->get("vodeoId");
        $rs = $tengxunyunVodService->getVodEncryptionPlayUrl($vodeoId);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseSuccess($rs);
    }

    
    public function tengxunyunSignatureAction(Request $request, TengxunyunVodService $tengxunyunVodService){
        $rs = $tengxunyunVodService->getUploadSignature();
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }
        return $this->responseSuccess($rs);
    }


    
    public function getAliyunVodPlayInfoAction(Request $request, AliyunVodService $aliyunVodService){
        $videoId = $request->get("videoId");
        $data = $aliyunVodService->getVodPlayInfo($videoId);
        if($this->error()->has()){
            return $this->responseError($this->error()->getLast());
        }

        return $this->responseSuccess($data);
    }

    
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
