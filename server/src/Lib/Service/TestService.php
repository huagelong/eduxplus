<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/14 21:44
 */

namespace App\Lib\Service;


use App\Lib\Base\BaseService;

class TestService extends BaseService
{

    public function show(){
//        $rs = $this->db->select("*")
//            ->from("admin_action_log")
//            ->where("id=1")->execute();
//        var_dump($this->getSQL());
//        var_dump($rs['ip']);
        return "hello";
    }

}
