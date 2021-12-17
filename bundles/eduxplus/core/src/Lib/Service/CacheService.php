<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/9 17:12
 */

namespace Eduxplus\CoreBundle\Lib\Service;


use Symfony\Contracts\Cache\CacheInterface;

/**
 * Class CacheService
 * @package Eduxplus\CoreBundle\Lib\Service
 */
class CacheService
{
    /**
     * @var CacheInterface
     */
    protected $cacheCLient;


    public function __construct($cacheCLient)
    {
        $this->cacheCLient = $cacheCLient;
    }


    protected function parseKey($key)
    {
        return strlen($key)>50 ? substr($key,0,50)."_".md5($key):$key;
    }

    /**
     * 获取缓存
     *
     * @param $key
     * @param null $default
     * @return null
     */
    public function get($key, $default = null)
    {
        $key = $this->parseKey($key);

        $result = $this->cacheCLient->get($key);
        if (!$result) return $default;
        $result = unserialize($result);
        return $result;
    }


    /**
     * 设置缓存
     * @param $key
     * @param $value
     * @param int $expire  过期时间 单位s
     * @return mixed
     */
    public function set($key, $value, $expire = -1)
    {
        $key = $this->parseKey($key);

        $value = serialize($value);
        if ($expire > 0) {
            $result = $this->cacheCLient->setex($key, $expire, $value);
        } else {
            $result = $this->cacheCLient->set($key, $value);
        }
        return $result;
    }

    /**
     * 删除key
     *
     * @param $key
     * @return mixed
     */
    public function del($key)
    {
        $key = $this->parseKey($key);

        return $this->cacheCLient->del($key);
    }
}
