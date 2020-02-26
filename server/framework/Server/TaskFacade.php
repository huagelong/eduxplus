<?php
/**
 * Trensy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      trensy, Inc.
 * @package         trensy/framework
 * @version         3.0.0
 */

namespace Trensy\Server;


use Trensy\Support\Facade;

class TaskFacade extends Facade
{
    protected static function setFacadeAccessor()
    {
        return "task";
    }
}