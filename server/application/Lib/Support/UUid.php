<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2018/2/28
 * Time: 15:50
 */

namespace Lib\Support;


use Ramsey\Uuid\Uuid as BaseUUid;
use PascalDeVink\ShortUuid\ShortUuid;

class UUid
{

    public static function getShortuuid()
    {
        $uuidObj = BaseUUid::uuid1();
        $shortUuid = new ShortUuid();
        return $shortUuid->encode($uuidObj);
    }

    public static function getUuid($str="")
    {
        if($str){
            $uuidObj = BaseUUid::uuid5(BaseUUid::NAMESPACE_DNS, $str);
        }else{
            $uuidObj = BaseUUid::uuid1();
        }
        return $uuidObj->toString();
    }

}