<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/3 20:20
 */

namespace App\Bundle\CenterBundle;

use App\Bundle\CenterBundle\DependencyInjection\CenterExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CenterBundle extends Bundle
{

    public function getContainerExtension()
    {
        return new CenterExtension();
    }

}
