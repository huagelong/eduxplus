<?php

/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2018/1/4
 * Time: 12:22
 */

namespace Lib\Support\File;

use Lib\Support\File\AliOss\Oss;
use Lib\Support\Xtrait\Helper;
use Lib\Support\File\Upyun\Upyun;

class File
{
    use Helper;

    protected $return;


    protected function getAdapter()
    {
        return new Oss();
    }


    public function save($remotePath,$localPath)
    {
        $obj = $this->getAdapter();
        $ret = $obj->save($remotePath,$localPath);
        if(stristr($ret, "http:")){
            $ret = ltrim($ret, "http:");
        }
        $this->return = $obj->getReturn();
        return $ret;
    }

    public function getObj($uri){
        return $this->getAdapter()->getObj($uri);
    }

    public function getSignedUrl($uri,  $timeout=10)
    {
        return $this->getAdapter()->getSignedUrl($uri, $timeout);
    }


    public function delete($uri)
    {
        return $this->getAdapter()->delete($uri);
    }

    public function getReturn()
    {
        return $this->return;
    }

}
