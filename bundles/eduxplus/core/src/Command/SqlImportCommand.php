<?php

namespace Eduxplus\CoreBundle\Command;

use Eduxplus\CoreBundle\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SqlImportCommand extends Command
{
    protected static $defaultName = 'app:sqlImport';

    protected function configure()
    {
        $this
            ->setDescription('import sql file')
            ->addArgument('path', InputArgument::OPTIONAL, 'sql file path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $path = $input->getArgument('path');
        $file = $path ? $path : "eduxplus.sql";

        $kernel = $this->getApplication()->getKernel();
        $container = $kernel->getContainer();

        $entityManager = $container->get('doctrine')->getManager();

        $sql = \file_get_contents($file);
        $pdo = $entityManager->getConnection()->getWrappedConnection();

        $pdo->beginTransaction();
        try {
            $statement = $pdo->prepare($sql);
            $statement->execute();
            while ($statement->nextRowset()) {
            }
            $pdo->commit();
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }

        $io->success('import - success!');

        return 0;
    }
}
