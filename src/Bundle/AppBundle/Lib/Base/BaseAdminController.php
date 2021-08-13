<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/4 11:47
 */

namespace App\Bundle\AppBundle\Lib\Base;

class BaseAdminController extends BaseController
{
    public function getUid()
    {
        $user = $this->getUser();
        if($user) return $user->getId();
        return 0;
    }
}
