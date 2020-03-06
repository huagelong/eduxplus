<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/3 20:20
 */

namespace App\ExpCenterBundle;

use App\ExpCenterBundle\DependencyInjection\ExpCenterExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
class ExpCenterBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ExpCenterExtension();
    }
}
