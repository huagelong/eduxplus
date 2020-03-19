<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/17 11:24
 */

namespace App\Lib\Service;


use App\Lib\Base\BaseService;
use Ramsey\Uuid\Uuid;

class HelperService extends BaseService
{

    public function getUuid(){
        $uuid = Uuid::uuid4();
        return $uuid->toString();
    }
}
