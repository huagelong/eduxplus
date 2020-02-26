<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2017/4/11
 * Time: 14:09
 */

namespace Lib\Base;

use Lib\Support\Xtrait\Helper;
use Trensy\MiddlewareAbstract;

abstract class BaseMiddleware extends MiddlewareAbstract
{
    use Helper;
}