<?php

namespace App\Command;

use App\Bundle\AppBundle\Lib\Service\File\AliyunOssService;
use App\Bundle\AppBundle\Lib\Service\UploadService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';
    protected $aliyunOssService;

    /**
     * TestCommand constructor.
     * @param AliyunOssService $aliyunOssService
     */
    public function __construct(AliyunOssService $aliyunOssService)
    {
        $this->aliyunOssService = $aliyunOssService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('test')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $filePath = $this->aliyunOssService->getBasePath()."/public/assets/images/404.png";
        $remoteFilePath = "article/".date('Y/m/d')."/test.png";
        $url = $this->aliyunOssService->upOss($remoteFilePath, $filePath);
        var_dump($url);
        return 0;
    }
}
