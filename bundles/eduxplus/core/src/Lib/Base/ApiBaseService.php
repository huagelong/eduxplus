<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/20 16:10
 */

namespace Eduxplus\CoreBundle\Lib\Base;


use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Eduxplus\CoreBundle\Repository\BaseUserRepository;

class ApiBaseService extends BaseService
{

    public function getUser()
    {
        throw new \LogicException("Method getUser Can Not Be Used");
    }

}
