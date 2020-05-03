<?php

namespace Cordo\Bundle\Backoffice;

use SplFileInfo;
use App\Register;
use FilesystemIterator;
use RecursiveIteratorIterator;
use Nette\PhpGenerator\PhpFile;
use RecursiveDirectoryIterator;
use Nette\PhpGenerator\ClassType;
use Doctrine\ORM\Tools\SchemaTool;
use Nette\PhpGenerator\PsrPrinter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Cordo\Core\UI\Console\Command\BaseConsoleCommand;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends BaseConsoleCommand
{
    private const DEFAULT_CONTEXT = 'Backoffice';

    protected static $defaultName = 'cordo/backoffice:install';

    protected $output;

    protected function configure()
    {
        $this
            ->setDescription('Initialize backoffice bundle.')
            ->setHelp('Copies files to app folder and executes schema update.')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('context', InputArgument::OPTIONAL),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = (object) $input->getArguments();

        $this->output = $output;
        $this->context = $params->context
            ?: self::DEFAULT_CONTEXT;

        $rootPath = realpath(dirname(__FILE__) . '/../app/');
        $targetPath = app_path() . $this->context;

        $this->copyFiles($rootPath, $targetPath);
        $this->parseFiles($targetPath);
        $this->registerModules($rootPath);
        $this->createSchema($output);

        return 0;
    }

    private function copyFiles(string $src, string $dst): void
    {
        if (file_exists($dst)) {
            $this->output->writeln("<error>Context {$this->context} already exists</error>");
            exit;
        }

        $this->output->writeln('Copying files...');

        mkdir($dst, 0755);
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                mkdir($dst . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
            } else {
                copy($item, $dst . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
            }
        }
    }

    private function parseFiles(string $src): void
    {
        if ($this->context == self::DEFAULT_CONTEXT) {
            return;
        }

        $this->output->writeln('Parsing files...');

        $directory = new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory);

        foreach ($iterator as $file) {
            $this->parseFile($file);
            $this->renameFile($file);
        }
    }

    private function parseFile(SplFileInfo $file): void
    {
        $fileContent = str_replace(
            ['Backoffice\\', 'backoffice'],
            [$this->context . '\\', strtolower($this->context)],
            (string) file_get_contents($file->getPathname())
        );

        file_put_contents($file->getPathname(), $fileContent);
    }

    private function renameFile(SplFileInfo $file): void
    {
        if (strpos($file->getFilename(), self::DEFAULT_CONTEXT)) {
            rename($file->getPathname(), str_replace(self::DEFAULT_CONTEXT, $this->context, $file->getPathname()));
        }
    }

    private function registerModules()
    {
        Register::add('Backoffice\Users');
        Register::add('Backoffice\Acl');
        Register::add('Backoffice\Auth');

        // add modules to app/Register file
        $class = ClassType::from(Register::class);
        $property = $class->getProperty('register');

        array_push($property->getValue(), 'Backoffice\Users');
        array_push($property->getValue(), 'Backoffice\Acl');
        array_push($property->getValue(), 'Backoffice\Auth');

        $file = (new PhpFile())->setStrictTypes();
        $namespace = $file->addNamespace('App');
        $namespace->add($class);
        $namespace->addUse('Cordo\Core\Application\Service\Register\ModulesRegister');

        file_put_contents(app_path() . 'Register.php', (new PsrPrinter)->printFile($file));
    }

    private function createSchema($output)
    {
        $em = require(root_path() . 'bootstrap/db.php');
        $tool = new SchemaTool($em);

        $classes = [
            $em->getClassMetadata("App\\{$this->context}\\Users\Domain\User"),
            $em->getClassMetadata("App\\{$this->context}\\Acl\Domain\Acl")
        ];
        $tool->createSchema($classes);

        $output->writeln('Schema created successfully.');
    }
}
