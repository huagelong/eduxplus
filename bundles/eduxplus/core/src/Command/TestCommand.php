<?php

namespace Eduxplus\CoreBundle\Command;


use Eduxplus\CoreBundle\Entity\BaseMenu;
use Eduxplus\CoreBundle\Entity\BaseRoleMenu;
use Eduxplus\CoreBundle\Lib\Service\HelperService;
use Doctrine\Persistence\ObjectManager;
use EasyWeChat\Kernel\Support\XML;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Eduxplus\EduxBundle\Service\Mall\OrderService;
use Eduxplus\EduxBundle\Service\Mall\PayService;
use Eduxplus\EduxBundle\Service\Mall\CouponService;
use Eduxplus\CoreBundle\Entity\BaseRole;
use Eduxplus\CoreBundle\Entity\BaseRoleUser;
use Eduxplus\CoreBundle\Lib\Service\Base\MobileMaskService;
use Eduxplus\CoreBundle\Entity\BaseUser;
use Eduxplus\CoreBundle\Entity\BaseOption;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'core:test';
    /**
     * TestCommand constructor.
     */
    public function __construct(

    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('test');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("core:test run success@".date('Y-m-d H:i:s'));
        return 0;
    }
}
