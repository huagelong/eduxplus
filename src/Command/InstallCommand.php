<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\ArrayInput;

class InstallCommand extends Command
{
    protected static $defaultName = 'app:install';

    protected function configure()
    {
        $this
            ->setDescription('Initialize the project')
            ->addArgument('all', InputArgument::OPTIONAL, 'init all data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //----1
        $command = $this->getApplication()->find('make:entity');
        $arguments = [
            'command' => 'make:entity',
            '--regenerate'=> true,
        ];
        $greetInput = new ArrayInput($arguments);
//        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);

        $command = $this->getApplication()->find('doctrine:schema:update');
        $arguments = [
            'command' => 'doctrine:schema:update',
            "--force"=>true,
        ];
        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);

        //----4  ./bin/console doctrine:fixtures:load --purge-with-truncate
        $command = $this->getApplication()->find('doctrine:fixtures:load');

        $argAll = $input->getArgument('all');

        if($argAll){
            $arguments = [
                'command' => 'doctrine:fixtures:load',
                "--purge-with-truncate"=>true
            ];
        }else{
            $arguments = [
                'command' => 'doctrine:fixtures:load',
                "--purge-with-truncate"=>true,
                 "--group"=>["InstallFixtures"]
            ];
        }

        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);

        $io->success('Initialization the project success!');

        return 0;
    }
}
