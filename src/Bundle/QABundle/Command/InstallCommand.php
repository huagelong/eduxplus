<?php

namespace App\Bundle\QABundle\Command;

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
    protected static $defaultName = 'qa:install';

    protected function configure()
    {
        $this
            ->setDescription('Initialize The QABundle');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //----1
        $command = $this->getApplication()->find('doctrine:schema:update');
        $arguments = [
            'command' => 'doctrine:schema:update',
            "--force" => true,
        ];
        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);

        //----4  ./bin/console doctrine:fixtures:load --purge-with-truncate
        $command = $this->getApplication()->find('doctrine:fixtures:load');

        $arguments = [
            'command' => 'doctrine:fixtures:load',
            "--append" => true,
            "--group" => ["QAInstallFixtures"]
        ];

        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);

        //---5
        $command = $this->getApplication()->find('doctrine:cache:clear-metadata');
        $arguments = [
            'command' => 'doctrine:cache:clear-metadata',
        ];
        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);
        //---6
        $command = $this->getApplication()->find('doctrine:cache:clear-collection-region');
        $arguments = [
            'command' => 'doctrine:cache:clear-collection-region',
            "--all" => true,
        ];
        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);
        ////---7
        $command = $this->getApplication()->find('doctrine:cache:clear-entity-region');
        $arguments = [
            'command' => 'doctrine:cache:clear-entity-region',
            "--all" => true,
        ];
        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);

        ////---8
        $command = $this->getApplication()->find('doctrine:cache:clear-query');
        $arguments = [
            'command' => 'doctrine:cache:clear-query',
        ];
        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);
        ////---9
        $command = $this->getApplication()->find('doctrine:cache:clear-query-region');
        $arguments = [
            'command' => 'doctrine:cache:clear-query-region',
        ];
        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);
        ////---10
        $command = $this->getApplication()->find('doctrine:cache:clear-result');
        $arguments = [
            'command' => 'doctrine:cache:clear-result',
        ];
        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);

        ////---11
        $command = $this->getApplication()->find('assets:install');
        $arguments = [
            'command' => 'assets:install',
        ];
        $greetInput = new ArrayInput($arguments);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);

        $io->success('Initialization the project success!');

        return 0;
    }
}
