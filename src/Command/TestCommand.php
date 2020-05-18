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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';
    protected $aliyunOssService;
    protected $uploadService;

    /**
     * TestCommand constructor.
     * @param AliyunOssService $aliyunOssService
     */
    public function __construct(AliyunOssService $aliyunOssService, UploadService $uploadService)
    {
        $this->aliyunOssService = $aliyunOssService;
        $this->uploadService = $uploadService;
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
        $filePath = $this->aliyunOssService->getBasePath()."/public/assets/images/fav.png";
        $fileObj = new UploadedFile($filePath, "fav.png",  'image/png', null, true);
        $url = $this->uploadService->upload($fileObj, "art");
//        $remoteFilePath = "article/".date('Y/m/d')."/test.png";
//        $url = $this->aliyunOssService->upOss($remoteFilePath, $filePath);
        var_dump($url);
        return 0;
    }
}
