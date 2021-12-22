<?php

namespace Eduxplus\CoreBundle\Command\Job;

use Eduxplus\WebsiteBundle\Service\MsgService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MsgCommand extends Command
{
    protected static $defaultName = 'job:msg';
    protected $msgService;

    public function __construct(MsgService $msgService){
        $this->msgService = $msgService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('添加消息');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
//        $io->note(sprintf('You passed an argument: %s', $arg1));
        $this->msgService->doQueue();

        $io->success('done');

        return 0;
    }
}
