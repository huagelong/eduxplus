<?php
namespace Eduxplus\CoreBundle\Doctrine\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Eduxplus\CoreBundle\Lib\Base\Facade;
use Godruoyi\Snowflake\Snowflake;
use Eduxplus\CoreBundle\Doctrine\Resolver\RedisSequenceResolver;

class SnowflakeGenerator extends AbstractIdGenerator
{

    /**
     * @var \Redis
     */
    private $redisCLient;


    public function __construct($redisCLient){
        $this->redisCLient = $redisCLient;
        //    $this->redisCLient = Facade::create("redis.persistence");
    }

    public function generate(EntityManager $em, $entity)
    {
        if($entity->getId()) return $entity->getId();
        $snowflake = new Snowflake;
        $resolver = new RedisSequenceResolver($this->redisCLient);
        $resolver->setCachePrefix("snowflake");
        $snowflake->setSequenceResolver($resolver);
        return $snowflake->id();
    }
}
