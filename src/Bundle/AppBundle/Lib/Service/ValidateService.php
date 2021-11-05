<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/17 11:24
 */

namespace App\Bundle\AppBundle\Lib\Service;


use App\Bundle\AppBundle\Lib\Base\Error;

class ValidateService
{

    public function error(){
        return new Error();
    }

    public function mobileValidate($mobile){
        if(preg_match('/^1[3456789]\d{9}$/i', $mobile)){
            return true;
        }else{
            return $this->error()->add("手机号码格式错误!");
        }
    }

    public  function emailValidate($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            return $this->error()->add("邮箱格式错误!");
        }
        return true;
    }

    public  function nicknameValidate($nickname){
        if(!preg_match("/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}\.]+$/u", $nickname)){
            return $this->error()->add("昵称中只能包括 中英文、数字、和下划线");
        }

        $nicklen = mb_strlen($nickname);
        if($nicklen<2||$nicklen>20){
            return $this->error()->add("昵称必须是2-20个字符");
        }
        return true;
    }

    public  function passwordValid($candidate) {
        $r1='/[A-Z]/';  //uppercase
        $r2='/[~!@#$%^&*()\-_=+{};:<,.>?]/';  // special char

        if(preg_match_all($r1,$candidate, $o)<1) {
            return $this->error()->add("密码必须包含至少一个大写字母");
        }

        if(preg_match_all($r2,$candidate, $o)<1) {
            return $this->error()->add("密码必须包含至少一个特殊符号：[~!@#$%^&*()\-_=+{};:<,.>?]");
        }

        if(mb_strlen($candidate)<6 || mb_strlen($candidate)>20) {
            return $this->error()->add("密码必须包含6-20个字符");
        }
        return true;
    }

    public function getLashError()
    {
        return $this->error()->getLast();
    }

}
