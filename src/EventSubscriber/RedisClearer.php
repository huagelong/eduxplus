<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/10/27 10:01
 */

namespace App\EventSubscriber;


use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

/**
 * 删除缓存
 *
 * Class RedisClearer
 * @package App\EventSubscriber
 */
class RedisClearer implements CacheClearerInterface
{
    /**
     * @var array Symfony\Contracts\Cache\CacheInterface;
     */
    private $clients = array();

    /**
     * Constructor
     *
     * @param array of SNC Redis clients
     */
    public function __construct($clients)
    {
        if (false === is_array($clients)) {
            $clients = array($clients);
        }
        $this->clients = $clients;
    }

    /**
     * Clear your cache
     */
    public function clear($cacheDir)
    {
        foreach ($this->clients as $client) {
            if ($client instanceof \Redis || $client instanceof \Predis\ClientInterface) {
                $rs = $client->flushDb();
                if($rs){
                    echo "redis:".$client->getHost()." flush success!\r\n";
                }
            }
        }
    }
}
