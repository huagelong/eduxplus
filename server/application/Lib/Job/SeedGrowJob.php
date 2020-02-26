<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2017/4/27
 * Time: 10:14
 */

namespace Lib\Job;


use Trensy\Log;
use Trensy\TaskAbstract;

final class SeedGrowJob extends TaskAbstract
{
    public function perform()
    {
        Log::debug('SeedGrowJob task run ');
//        file_put_contents(ROOT_PATH."/job.txt", date('Y-m-d H:i:s').' SeedGrowJob task run \r\n', FILE_APPEND);
        return true;
    }

}