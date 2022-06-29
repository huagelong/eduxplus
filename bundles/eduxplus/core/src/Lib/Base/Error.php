<?php
/**
 * Created by PhpStorm.
 * User: huagelong
 * Date: 2017/4/13
 * Time: 11:10
 */

namespace Eduxplus\CoreBundle\Lib\Base;


class Error
{

    protected static $errorData = [];

    public function add($msg)
    {

        self::$errorData[] = $msg;

        return false;
    }

    public function getAll()
    {
        return self::$errorData;
    }

    public function getLast()
    {
        return end(self::$errorData);
    }

    public function has()
    {
        return self::$errorData?true:false;
    }
}
