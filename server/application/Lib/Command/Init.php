<?php
/**
 * Created by PhpStorm.
 * User: wangkh
 * Date: 2018/9/14
 * Time: 15:41
 */

namespace Lib\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trensy\Log;

class Init extends Base
{

    protected function configure()
    {
        $this->setName('init')
            ->setDescription('site init ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = STORAGE_PATH;
        if(!is_dir($dir)) mkdir($dir, 0777 ,true);
        $dir = STORAGE_PATH."/compile";
        if(!is_dir($dir)) mkdir($dir, 0777 ,true);
        $dir = STORAGE_PATH."/tmp/upload";
        if(!is_dir($dir)) mkdir($dir, 0777 ,true);
        $dir = STORAGE_PATH."/runtime";
        if(!is_dir($dir)) mkdir($dir, 0777 ,true);

        //执行数据库初始化
        $command = $this->getApplication()->find('db:sync');
        $arguments = array(
            'command' => 'db:sync'
        );

        $dbSyncInput = new ArrayInput($arguments);
        $returnCode = $command->run($dbSyncInput, $output);
        debug($returnCode);
        Log::sysinfo("init success!");
        return 0;
    }
}
