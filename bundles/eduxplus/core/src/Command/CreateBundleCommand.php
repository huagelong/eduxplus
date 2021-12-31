<?php

namespace Eduxplus\CoreBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;
use function Symfony\Component\String\u;


class CreateBundleCommand extends Command
{
    const SEPARATOR = '/';
    const BUNDLE_ROOT = './../../lib';

    // to make your command lazily loaded, configure the $defaultName static property,
    // so it will be instantiated only when the command is actually called.
    protected static $defaultName = 'bundle:create';

    /**
     * @var SymfonyStyle
     */
    private $io;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates the bundle skeleton in lib directory')
            ->setHelp($this->getCommandHelp())
            ->addArgument('domain-name', InputArgument::OPTIONAL, 'The domain name of the new bundle. E.g. "Acme"')
            ->addArgument('bundle-name', InputArgument::OPTIONAL, 'The bundle name. E.g. "Foo"')
            ->addArgument('bundle-description', InputArgument::OPTIONAL, 'The bundle description. E.g. "This bundle adds support for ..."')
            ->addArgument('your-name', InputArgument::OPTIONAL, 'Your name. E.g. "John Doe"')
            ->addArgument('your-email', InputArgument::OPTIONAL, 'Your email. E.g. "johndoe@email.com"')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (
            null !== $input->getArgument('domain-name') &&
            null !== $input->getArgument('bundle-name') &&
            null !== $input->getArgument('bundle-description') &&
            null !== $input->getArgument('your-name')  &&
            null !== $input->getArgument('your-email')) {

            $this->validateDomainName($input->getArgument('domain-name'));
            $this->validateBundleName($input->getArgument('bundle-name'));
            $this->validateBundleDescription($input->getArgument('bundle-description'));
            $this->validateFullName($input->getArgument('your-name'));
            $this->validateEmail($input->getArgument('your-email'));
            return;
        }

        $this->io->title('Create Bundle Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console bundle:create domain-name bundle-name bundle-description your-name your-email',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);

        // Ask for arguments if they are not defined
        $this->askForArgument($input, 'domain-name', 'The domain name', 'validateDomainName');
        $this->askForArgument($input, 'bundle-name', 'The bundle name', 'validateBundleName');
        $this->askForArgument($input, 'bundle-description', 'The bundle description', 'validateBundleDescription');
        $this->askForArgument($input, 'your-name', 'Your Full Name', 'validateFullName');
        $this->askForArgument($input, 'your-email', 'Your Email', 'validateEmail');

    }


    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (null === u($email)->indexOf('@')) {
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }

    public function validateFullName(?string $fullName): string
    {
        if (empty($fullName)) {
            throw new InvalidArgumentException('The full name can not be empty.');
        }

        return $fullName;
    }

    public function validateDomainName(?string $domainName): string
    {
        if (empty($domainName)) {
            throw new InvalidArgumentException('The domain name can not be empty.');
        }

        if (1 !== preg_match('/^[A-Za-z-]+$/', $domainName)) {
            throw new InvalidArgumentException('The domain name must contain only latin characters and dashes.');
        }

        return $domainName;
    }

    public function validateBundleName(?string $bundleName): string
    {
        if (empty($bundleName)) {
            throw new InvalidArgumentException('The bundle name can not be empty.');
        }

        if (1 !== preg_match('/^[A-Za-z-]+$/', $bundleName)) {
            throw new InvalidArgumentException('The bundle name must contain only latin characters and dashes.');
        }

        return $bundleName;
    }

    public function validateBundleDescription(?string $description): string
    {
        if (empty($description)) {
            throw new InvalidArgumentException('The bundle description can not be empty.');
        }

        return $description;
    }


    private function askForArgument(InputInterface $input, $key, $text, $validationMethod)
    {
        // Ask for argument if it's not defined
        $argument = $input->getArgument($key);
        if (null !== $argument) {
            $this->io->text(" > <info>{$text}</info>: ".u('*')->repeat(u($argument)->length()));
        } else {
            $argument = $this->io->ask($text, null, [$this, $validationMethod]);
            $input->setArgument($key, $argument);
        }
    }


    private function createDir($dir)
    {
        if (!mkdir($dir, 0755, true)) {
            die('Error creating directory ' . $dir);
        }

        return true;
    }

    private function copyFile($oldPath, $path)
    {
        if (!copy($oldPath, $path)) {
            die('Error renaming file ' . $oldPath);
        }
    }

    private function moveFile($oldPath, $path)
    {
        if (!rename($oldPath, $path)) {
            die('Error renaming file ' . $oldPath);
        }
    }


    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('create-bundle-command');

        $domainName = $input->getArgument('domain-name');
        $bundleName = $input->getArgument('bundle-name');
        $bundleDescription = $input->getArgument('bundle-description');
        $yourName = $input->getArgument('your-name');
        $yourEmail = $input->getArgument('your-email');

        $domainName = $this->sanitizeDomainName($domainName);
        $bundleName = $this->sanitizeBundleName($bundleName);
        $newDir = "./bundles/{$domainName}/{$bundleName}";
        $this->createDir($newDir);
        $this->xCopy("./bundles/eduxplus/core/lib/acme/foo", $newDir);

        $path = "{$newDir}/src/AcmeFooBundle.php";
        $this->replaceFileContentsBundleFullName($domainName, $bundleName, $path, $newDir);
        $path = "{$newDir}/src/DependencyInjection/AcmeFooExtension.php";
        $this->replaceFileContentsBundleFullName($domainName, $bundleName, $path, $newDir);

        $domainNameTmp = ucfirst($domainName);
        $bundleNameTmp = ucfirst($bundleName);
        $this->batchReplace($newDir, "/AcmeFoo/", $domainNameTmp.$bundleNameTmp);
        $this->batchReplace($newDir, "/Acme\\\Foo/", $domainNameTmp."\\".$bundleNameTmp);
        $this->batchReplace($newDir, "/acme_foo/", $domainName."_".$bundleName);
        $this->repalceComposer($domainName, $bundleName, $newDir, $bundleDescription, $yourName, $yourEmail);

        $this->io->success(sprintf('The bundle skeleton was successfully created at: bundles/%s/%s', $domainName, $bundleName));

        $event = $stopwatch->stop('create-bundle-command');
        if ($output->isVerbose()) {
           $this->io->comment(sprintf('Elapsed time: %.2f ms / Consumed memory: %.2f MB', $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return 0;
    }

    private function repalceComposer($domainName, $bundleName, $path, $bundleDescription, $yourName, $yourEmail){
        $path = $path."/composer.json";
        $content = file_get_contents($path);
        $content = preg_replace("/acme/", $domainName, $content);
        $content = preg_replace("/foo/", $bundleName, $content);
        $content = preg_replace("/Acme/", ucfirst($domainName), $content);
        $content = preg_replace("/Foo/", ucfirst($bundleName), $content);
        $content = preg_replace("/bundleDescription/", ucfirst($bundleDescription), $content);
        $content = preg_replace("/yourName/", ucfirst($yourName), $content);
        $content = preg_replace("/yourEmail/", ucfirst($yourEmail), $content);
        file_put_contents($path, $content);
    }


    private function replaceFileContentsBundleFullName($domainName, $bundleName, $path){
        $domainName = ucfirst($domainName);
        $bundleName = ucfirst($bundleName);
        $newPath = preg_replace("/AcmeFoo/", $domainName.$bundleName, $path);
        $this->moveFile($path, $newPath);
        return true;
    }


    private function batchReplace($sourcePath, $reg, $replaceTo, $ext="php,yaml") {
        if (!is_dir($sourcePath)) {
            return false;
        }

        $handle = dir ($sourcePath);
        while ( $entry = $handle->read() ) {
            if (($entry != ".") && ($entry != "..")) {
                $tmpPath = $sourcePath . "/" . $entry;

                if (is_dir ($tmpPath)) {
                    $this->batchReplace($tmpPath, $reg, $replaceTo, $ext);
                } else {
                    //开始替换
                    $pathinfo = pathinfo($tmpPath);
                    $extArr = explode(",", $ext);
                    if(isset($pathinfo['extension']) && in_array($pathinfo['extension'], $extArr)){
                        echo $tmpPath."\n";
                        $tmpData = file_get_contents($tmpPath);
                        $tmpData = preg_replace($reg, $replaceTo, $tmpData);
                        file_put_contents($tmpPath, $tmpData);
                    }
                }
            }
        }
        return true;
    }
    
    private function sanitizeDomainName($domainName): string
    {
        $domainName = preg_replace('/([A-Z])/', '-$1', $domainName);
        $domainName = str_replace('--', '-', $domainName);
        if (strpos($domainName, '-') === 0) {
            $domainName = substr($domainName, 1);
        }

        return strtolower($domainName);
    }

    private function sanitizeBundleName($bundleName): string
    {
        $bundleName = preg_replace('/([A-Z])/', '-$1', $bundleName);
        $bundleName = str_replace('--', '-', $bundleName);
        if (strpos($bundleName, '-') === 0) {
            $bundleName = substr($bundleName, 1);
        }

        $bundleName = strtolower($bundleName);
        
        return $bundleName;
    }

    public function xCopy($source, $destination, $child = 1, $except=[])
    {
        if (!is_dir($source)) {
            return false;
        }
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }
        $handle = dir($source);
        $source = $this->formatPath($source);
        while ($entry = $handle->read()) {
            if (($entry != ".") && ($entry != "..")) {
                if (is_dir($source . $entry)) {
                    if ($child) {
                        if($except && in_array($source . $entry, $except)){
                            continue;
                        }
                        $this->xCopy($source . $entry, $destination . "/" . $entry, $child, $except);
                    }
                } else {
                    copy($source . $entry, $destination . "/" . $entry);
                }
            }
        }
        return true;
    }


    public function formatPath($path)
    {
        if ('/' == substr($path, -1)) {
            return $path;
        }

        return $path . '/';
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> command creates a bundle skeleton in /lib:

  <info>php %command.full_name%</info> <comment>domain-name bundle-name</comment>

By default the command creates AcmeFooBundle in /lib/acme/foo directory.

If you omit any of the three required arguments, the command will ask you to
provide the missing values:

  # command will ask you for the domain name
  <info>php %command.full_name%</info> <comment>bundle-name</comment>

  # command will ask you for all arguments
  <info>php %command.full_name%</info>

HELP;
    }
}
