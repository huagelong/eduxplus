<?php

namespace Glob\Controllers;

use Lib\Support\Error;

final class FileController extends BaseController
{

    /**
     * @Inject
     * @var \Lib\Service\FileService
     */
    public $fileService;

    /**
     * 文件上传到云存储
     */
    public function upyun()
    {
        $request = $this->getRequest();
        $name=$request->get('name');
        if($name){
            $file = $request->files->get($name);
        }else{
            $file = $request->files->get("upfile");
        }

        $groupCode = $request->get("code");

        if(!$file) return $this->responseError("上传文件不能为空!",[]);

        if(!$groupCode) return $this->responseError("code码不能为空!",[]);
        $size = isset($file['size'])?$file['size']:0;
        $size = floatval(($size/(1024*1024)));
        if($size>10) return $this->responseError("上传文件大小不能大于10m!",[]);
        $size = "10M";
        try{
            $data = $this->fileService->upYun($file, $groupCode, $size);

            if(!$data) return $this->responseError(Error::getLast(),[]);
            return $this->response($data);
        }catch (\Exception $e){
            return $this->responseError($e->getMessage(),[]);
        }
    }


}
