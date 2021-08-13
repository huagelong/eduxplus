<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/28 21:10
 */

namespace App\Bundle;


use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
{

    public function load($resource, $type = null)
    {
        $source = __DIR__;
        $handle = dir ($source);
        $collection = new RouteCollection();
        while ( $entry = $handle->read() ) {
            if (($entry != ".") && ($entry != "..")) {
                $bundleDir = $source . "/" . $entry;
                if (is_dir ($bundleDir)) {
                    if(is_file($bundleDir."/Resources/config/routing.yaml")){
                        $resource = $bundleDir."/Resources/config/routing.yaml";
                        $importedRoutes = $this->import($resource, 'yaml');
                        $collection->addCollection($importedRoutes);
                    }
                }
            }
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }
}
