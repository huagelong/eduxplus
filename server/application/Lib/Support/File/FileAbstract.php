<?php

/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2018/1/4
 * Time: 12:22
 */

namespace Lib\Support\File;

abstract class FileAbstract
{
    abstract public function delete($uri);
    abstract public function getSignedUrl($uri, $timeout=10);
    abstract public function getObj($uri);
    abstract public function save($remotePath,$localPath);
    abstract public function getReturn();
}