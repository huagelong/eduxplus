<?php

declare(strict_types=1);

namespace Eduxplus\CoreBundle\Doctrine\Resolver;
use Godruoyi\Snowflake\SequenceResolver;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisSequenceResolver implements SequenceResolver
{
    /**
     * The redis client instance.
     *
     * @var \Redis
     */
    protected $redis;

    /**
     * The cache prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Init resolve instance, must connectioned.
     */
    public function __construct($redisCLient)
    {
        $this->redis = $redisCLient;
    }

    /**
     *  {@inheritdoc}
     */
    public function sequence(int $currentTime) : int
    {
        $lua = "return redis.call('exists',KEYS[1])<1 and redis.call('psetex',KEYS[1],ARGV[2],ARGV[1])";

        $key = $this->prefix.$currentTime;
//        if(call_user_func_array([$this->redis, 'eval'], array_merge([$lua, 1], [$key], [1, 1000]))){
//            return 0;
//        }
        if ($this->redis->eval($lua, [$key, 1, 1000], 1)) {
            return 0;
        }
        return $this->redis->incrby($key, 1);
    }

    /**
     * Set cacge prefix.
     */
    public function setCachePrefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }
}
