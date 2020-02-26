<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2018/1/4
 * Time: 13:45
 */

namespace Lib\Service;


use Lib\Base\BaseService;
use Lib\Dao\System\FileDao;
use Lib\Dao\System\FileGroupDao;
use Lib\Dao\System\FileUsedDao;
use Lib\Support\Error;
use Lib\Support\File\File;
use Lib\Support\UUid;
use Upload\Storage\FileSystem;
use Upload\File as UFile;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;

final class FileService extends BaseService
{

    /**
     * @Inject
     * @var \Lib\Dao\System\FileDao
     */
    public $fileDao;

    /**
     * @Inject
     * @var \Lib\Dao\System\FileGroupDao
     */
    public $fileGroupDao;

    /**
     * @Inject
     * @var \Lib\Dao\System\FileUsedDao
     */
    public $fileUsedDao;


    public $units = array(
        'b' => 1,
        'k' => 1024,
        'm' => 1048576,
        'g' => 1073741824
    );

    protected $mimeType = [
        'image/png',
        'image/x-ms-bmp',
        'image/gif',
        'image/jpeg',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-word',
        'application/msword',
        'application/vnd.ms-excel',
        'application/msexcel',
        'application/x-msexcel',
        'application/x-ms-excel',
        'application/x-excel',
        'application/x-dos_ms_excel',
        'application/xls',
        'application/x-xls',
        'application/vnd.ms-office',
        'application/pdf',
        'application/x-rar-compressed',
        'application/x-rar',
        'application/octet-stream',
        'application/zip',
        'text/plain'
    ];

    protected $size = '5M';

    public function getSignedUrl($uri, $timeout=10)
    {
        $yunObj = new File();
        $obj = $yunObj->getObj($uri);

        return $yunObj->getSignedUrl($obj, $timeout);
    }


    public function delUrl($uri)
    {
        try{
            $yunObj = new File();
            $obj = $yunObj->getObj($uri);
            $check = $yunObj->delete($obj);
            if($check){
                $where = [];
                $where['obj'] = $obj;
                $fileId = $this->fileDao->getField("id",$where);
                $check = $this->fileDao->delete(['id'=>$fileId]);
                if($check) $this->fileUsedDao->delete(['file_id'=>$fileId]);
            }
        }catch (\Exception $e){
            Error::add($e->getMessage());
        }
    }

    /**
     * 推送到云存储
     *
     * @param $localFilePath
     * @param $remoteFilePath
     * @param $groupCode
     * @param int $isBackup
     * @param int $uid
     * @param string $originalzName
     * @return array|bool
     */
    public function push($localFilePath, $remoteFilePath, $groupCode,$uid=0, $originalzName='')
    {
        //检查group是否存在
        $check = $this->fileGroupDao->get(['code'=>$groupCode]);
        if(!$check) return Error::add("文件组[$groupCode]不存在!");

        $yunObj = new File();
        $uri = $yunObj->save($remoteFilePath, $localFilePath);
        $retData = json_encode($yunObj->getReturn());
        if($uri){
            $size = ceil((filesize($localFilePath)/1024));//kb
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $localFilePath);

            finfo_close($finfo);
            //保存文件地址
            $idata = [];
            $idata['group_id'] = $check['id'];
            $idata['uid'] = $uid;
            $idata['obj'] = $yunObj->getObj($uri);
            $idata['mime'] = $mimetype;
            $idata['size'] = $size;
            $idata['thirdpart_data'] = $retData;
            if($originalzName) $idata['originalz_name'] = $originalzName;

            $fileId = $this->fileDao->autoAdd($idata);

            return [$fileId, $uri];
        }

        return false;
    }

    /**
     * 绑定用户
     *
     * @param $fileId
     * @param $uid
     * @return mixed
     */
    public function bindUser($fileId, $uid)
    {
        $udata = [];
        $udata['uid'] = $uid;

        $where = [];
        $where['id'] = $fileId;

        return $this->fileDao->autoUpdate($udata, $where);
    }

    /**
     * 实体绑定
     *
     * @param $fileIds
     * @param $targetType
     * @param $targetId
     */
    public function bindTarget($fileIds, $targetType, $targetId)
    {
        $fileIds = is_array($fileIds)?$fileIds:[$fileIds];

        foreach ($fileIds as $fileId){
            $idata = [];
            $idata['file_id'] = $fileId;
            $idata['target_type'] = $targetType;
            $idata['target_id'] = $targetId;
            $this->fileUsedDao->autoAdd($idata);
        }

        return true;
    }

    public function upload($fileArr, $isBackup=0, $size=0, $mimeType=[])
    {
        $_FILES['upfile'] = $fileArr;

        if($size){
            $this->size = $size;
        }

        if($mimeType){
            $this->mimeType = array_merge($this->mimeType, $mimeType);
        }

        //是否备份，传统文件上传

        if($isBackup){
            $storagePath = sys_get_temp_dir()."/upload";
            if(!is_dir($storagePath)){
                mkdir($storagePath, 0777 ,true);
            }

            $storage = new FileSystem($storagePath);
            $file = new UFile('upfile', $storage);

            $originalName = $file->getName();
//            $newFilename = sha1(uniqid() . $originalName);
            $newFilename = UUid::getUuid();
            $file->setName($newFilename);

            $file->addValidations(array(
                new Mimetype( $this->mimeType ),
                new Size( $this->size )
            ));

            if($file->getSize() == 0){
                return Error::add("不能上传空文件");
            }

            try
            {
                $sSuccess = $file->upload();

                if($sSuccess){
                    $newFilePath = $storagePath."/".$newFilename.".".$file->getExtension();
                    $originalFile = $originalName.".".$file->getExtension();
                    return [$newFilePath, $originalFile];
                }else{
                    return false;
                }
            }
            catch (\Exception $e)
            {
                return Error::add($file->getErrors());
            }
        }else{
            $mimeType = isset($fileArr['type'])?$fileArr['type']:'';
            if(!in_array($mimeType, $this->mimeType)) return Error::add("此类文件不准上传");
            $upsize = isset($fileArr['size'])?$fileArr['size']:0;
            $size = UFile::humanReadableToBytes($this->size);
            if($upsize>$size) return Error::add("上传文件超过最大尺寸限制");
            $tmpName = isset($fileArr['tmp_name'])?$fileArr['tmp_name']:"";
            $name = isset($fileArr['name'])?$fileArr['name']:"";
            return [$tmpName, $name];
        }
    }

    /**
     *
     * 上传到云服务
     *
     * @param $files
     * @param $remoteFilePath
     * @param $groupCode
     * @param int $uid
     * @param int $size
     * @param array $mimeType
     * @param int $isBackup
     * @return array|bool
     */
    public function upYun($files, $groupCode='default', $size=0, $mimeType=[], $uid=0, $isBackup=0)
    {
        $pathArr = $this->upload($files, $isBackup, $size, $mimeType);
        if($pathArr !== false){
            list($localFilePath, $originalName) = $pathArr;
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
//            $newFilename = sha1(uniqid() . $originalName);
            $newFilename = UUid::getUuid();
            $remoteFilePath = "eduxplus/".$groupCode."/".date('Y/m/d')."/".$newFilename.".".$ext;
            return $this->push($localFilePath, $remoteFilePath, $groupCode, $uid, $originalName);
        }
        return false;
    }

}
