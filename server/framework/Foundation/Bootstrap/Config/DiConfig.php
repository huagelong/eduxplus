<?php
/**
 *  di setting
 * Trensy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      trensy, Inc.
 * @package         trensy/framework
 * @version         3.0.0
 */

namespace Trensy\Foundation\Bootstrap\Config;


class DiConfig
{

    public static function getOptions()
    {
        return [
            "context" =>\Trensy\Context::class,
            "log" => \Trensy\Context::class,
            "db"=>\Trensy\DB::class,
            "session" => \Trensy\Foundation\Bootstrap\Session::class,
        ];
    }

}