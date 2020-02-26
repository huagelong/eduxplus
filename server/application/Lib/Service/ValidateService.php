<?php
/**
 * User: Peter Wang
 * Date: 17/2/4
 * Time: 下午5:34
 */

namespace Lib\Service;


use Lib\Base\BaseService;
use Lib\Support\Error;

final  class ValidateService extends BaseService
{
    public static function emailValidate($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            return Error::add("邮箱格式错误!");
        }
        return true;
    }

    public static function nicknameValidate($nickname){
        if(!preg_match("/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}\.]+$/u", $nickname)){
            return Error::add("昵称中只能包括 中英文、数字、和下划线");
        }

        $nicklen = mb_strlen($nickname);
        if($nicklen<2||$nicklen>20){
            return Error::add("昵称必须是2-20个字符");
        }
        return true;
    }

    public static function passwordValid($candidate) {
        $r1='/[A-Z]/';  //uppercase
        $r2='/[~!@#$%^&*()\-_=+{};:<,.>?]/';  // special char

        if(preg_match_all($r1,$candidate, $o)<1) {
            return Error::add("密码必须包含至少一个大写字母");
        }

//        if(preg_match_all($r2,$candidate, $o)<1) {
//            return Error::add("密码必须包含至少一个特殊符号：[~!@#$%^&*()\-_=+{};:<,.>?]");
//        }

        if(mb_strlen($candidate)<6 || mb_strlen($candidate)>20) {
            return Error::add("密码必须包含6-20个字符");
        }
        return true;
    }

    public static function getLashError()
    {
        return Error::getLast();
    }

}