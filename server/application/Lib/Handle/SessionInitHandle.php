<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2017/4/13
 * Time: 15:47
 */

namespace Lib\Handle;


use Lib\Support\Xtrait\Helper;
use Trensy\Context;

class SessionInitHandle
{
    use Helper;

    public function perform()
    {
        $host = $this->getDomain();
        $domain = $this->parseHost($host);
        $this->config()->set("app.session.domain", $domain);
//        debug($domain);
    }
}