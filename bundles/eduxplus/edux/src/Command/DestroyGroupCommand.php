<?php

namespace Eduxplus\EduxBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Eduxplus\EduxBundle\Service\Teach\ChapterService;
use Symfony\Component\Console\Style\SymfonyStyle;

class DestroyGroupCommand extends Command
{
    protected static $defaultName = 'edux:destroyGroup';
    protected $chapterService;
    /**
     * TestCommand constructor.
     */
    public function __construct(
        ChapterService $chapterService
    ) {
        $this->chapterService = $chapterService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('deleting an Expired Group');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->chapterService->destoryImGroup();
        $io->success(' destory im Group success!');
        return 0;
    }

}
