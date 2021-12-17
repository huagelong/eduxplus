<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/15 22:22
 */

namespace Eduxplus\CoreBundle\Exception;


use Throwable;

class LoginExpiredException extends \Exception
{
    public function __construct(
        string $message = "",
        int $code = 408,
        Throwable $previous = null
    ) {
        parent::__construct('账号在其他地方登录，请重新登录!', $code, $previous);
    }
}
