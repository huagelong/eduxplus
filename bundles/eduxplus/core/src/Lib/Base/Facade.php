<?php


namespace Eduxplus\CoreBundle\Lib\Base;


use Symfony\Component\DependencyInjection\ContainerInterface;

class Facade
{
    /**
     * self|null
     */
    private static $instance = null;

    /**
     * ContainerInterface
     */
    private static $myContainer;

    /**
     * @param ContainerInterface $container
     */
    private function __construct(ContainerInterface $container)
    {
        self::$myContainer = $container;
    }

    /**
     * @param string $serviceId
     *
     * @return object
     * @throws \Exception
     */
    public static function create($serviceId)
    {
        if (null === self::$instance) {
            throw new \Exception("Facade is not instantiated");
        }

        return self::$myContainer->get($serviceId);
    }

    public static function has($serviceId){
        return self::$myContainer->has($serviceId);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return null|Facade
     */
    public static function init(ContainerInterface $container)
    {
        if (null === self::$instance) {
            self::$instance = new self($container);
        }

        return self::$instance;
    }
}
