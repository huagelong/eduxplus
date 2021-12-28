<?php

namespace Eduxplus\CoreBundle\Command;

use Eduxplus\CoreBundle\Lib\Base\BaseService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenDirCommand extends Command
{
    protected static $defaultName = 'core:gen:dir';
    protected $baseService;

    public function __construct(BaseService $baseService)
    {
        $this->baseService = $baseService;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Initialize the project Directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $paths= [
            $this->baseService->getBasePath()."/var",
            $this->baseService->getBasePath()."/var/cache",
            $this->baseService->getBasePath()."/var/tmp",
            $this->baseService->getBasePath()."/var/cache/".$_SERVER['APP_ENV'],
            $this->baseService->getBasePath()."/var/".$_SERVER['APP_ENV']."/profiler",
            $this->baseService->getBasePath()."/var/log",
        ];

        try {
            foreach ($paths as $path){
                if(!is_dir($path)){
                    mkdir($path, 0777 , true);
                }
            }
        }catch (\Exception $e){
            $io->error($e->getMessage());
        }

        $io->success('make directory success!');

        return 0;
    }
}
