<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/15 22:22
 */

namespace App\Exception;


use Throwable;

class EmptyBodyException extends \Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct('The body of the POST/PUT method cannot be empty', $code, $previous);
    }
}
