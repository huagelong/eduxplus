<?php
namespace Eduxplus\CoreBundle\Doctrine\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Eduxplus\CoreBundle\Lib\Base\Facade;
use Godruoyi\Snowflake\Snowflake;
use Eduxplus\CoreBundle\Doctrine\Resolver\RedisSequenceResolver;
use function PHPUnit\Framework\throwException;

class SnowflakeGenerator extends AbstractIdGenerator
{

    public function generate(EntityManager $em, $entity)
    {
        if($entity->getId()) return $entity->getId();

        $redisCLient = Facade::create("redis.persistence");

        $snowflake = new Snowflake;
        $resolver = new RedisSequenceResolver($redisCLient);
        $resolver->setCachePrefix("snowflake");
        $snowflake->setSequenceResolver($resolver);
        return $snowflake->id();
    }
}
