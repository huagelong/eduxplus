<?php
/**
 *
 *
 * User: wangkaihui
 * Date: 2018/1/23
 * Time: 15:51
 */

namespace Lib\Command;

use Trensy\Config;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trensy\Di;
use Trensy\Log;
use Trensy\Support\Dir;

class ClearCache extends Base
{

    protected function configure()
    {
        $this->setName('clear:cache')
            ->setDescription('upload resource to yun');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        try{
            $path = STORAGE_PATH."/compile";
            $files = Dir::scan($path, Dir::SCAN_BFS);
            if($files) {
                foreach ($files as $v) {
                    unlink($v);
                }
            }
            return 0;
        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
        }
    }

}
