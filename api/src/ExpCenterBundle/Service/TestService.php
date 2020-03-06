<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/5 11:03
 */

namespace App\ExpCenterBundle\Service;


use App\ExpCenterBundle\Lib\Base\BaseService;

class TestService extends BaseService
{

    public function test(){
        return $this->getParameter("app.name");
    }

}
