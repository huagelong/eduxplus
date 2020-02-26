<?php
namespace Lib\Base;

use Lib\Support\Xtrait\Helper;
use Trensy\ServceAbstract;
use Trensy\Foundation\Storage\Redis;
use Trensy\Storage\Cache\Adapter\RedisCache;

class BaseService extends ServceAbstract
{
    use Helper;

    /**
     * @Inject
     * @var Redis
     */
    protected $redis;

    /**
     *  @Inject
     * @var RedisCache
     */
    protected $cache;

    protected function getTablePre()
    {
        return $this->config()->get("storage.server.pdo.prefix");
    }

    /**
     *  @return \Trensy\Http\Request
     */
    public  function getRequest()
    {
        return \Trensy\Context::request();
    }

    /**
     * @return \Trensy\Http\response
     */
    public function getResponse()
    {
        return \Trensy\Context::response();
    }

}
