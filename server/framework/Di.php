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

namespace Trensy;

use Trensy\Di\Exception\DiNotDefinedException;

class Di
{
    /**
     *  Container instance
     *
     */
    protected static $containerInstance = null;


    /**
     * @return \DI\Container
     */
    public static function getContainer()
    {
        if (!self::$containerInstance) {
            $builder = new \DI\ContainerBuilder();
            $debug = Config::get("app.debug");
            if(!$debug){
                $cacheDir = STORAGE_PATH."/runtime/di";
                $builder->enableCompilation($cacheDir);
            }
            $builder->useAnnotations(true);
            $container = $builder->build();
            self::$containerInstance =$container;
        }
        return self::$containerInstance;
    }

    public static function get($name)
    {
        return self::getContainer()->get($name);
    }

    public static function make($name, array $parameters = [])
    {
        return self::getContainer()->make($name, $parameters);
    }

    public static function has($name)
    {
        return self::getContainer()->has($name);
    }

    public static function set(string $name, $value){
        return self::getContainer()->set($name, $value);
    }

    public static function injectOn($instance)
    {
        return self::getContainer()->injectOn($instance);
    }

    public static function call($callable, array $parameters = [])
    {
        return self::getContainer()->call($callable, $parameters);
    }

    public static function getKnownEntryNames(){
        return self::getContainer()->getKnownEntryNames();
    }

    public static function debugEntry(string $name){
        return self::getContainer()->debugEntry($name);
    }
}