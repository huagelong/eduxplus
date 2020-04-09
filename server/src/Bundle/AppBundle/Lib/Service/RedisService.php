<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/9 17:12
 */

namespace App\Bundle\AppBundle\Lib\Service;


/**
 * Class RedisService
 * @package App\Bundle\AppBundle\Lib\Service
 * @method set
 */
class RedisService
{
    /**
     * @var \redis
     */
    protected $redisCLient;


    public function __construct($redisCLient)
    {
        $this->redisCLient = $redisCLient;
    }


    public function __call($name, $arguments)
    {
        if ($arguments) {
            $result =  call_user_func_array([$this->redisCLient, $name], $arguments);
        } else {
            $result = $this->redisCLient->$name();
        }
        return $result;
    }
}
