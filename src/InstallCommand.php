<?php

namespace Cordo\Bundle\Backoffice;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Cordo\Core\UI\Console\Command\BaseConsoleCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Cordo\Core\Application\Service\Bundle\BundleInstaller;
use Cordo\Core\Application\Service\Bundle\BundleInstallerException;

class InstallCommand extends BaseConsoleCommand
{
    private const DEFAULT_CONTEXT = 'Backoffice';

    protected static $defaultName = 'cordo/backoffice:install';

    protected function configure()
    {
        $this
            ->setDescription('Install backoffice bundle.')
            ->setHelp('Copies files, registers modules and updates schema.')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('context', InputArgument::OPTIONAL),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = (object) $input->getArguments();

        $this->context = $params->context ?: self::DEFAULT_CONTEXT;

        $rootPath = realpath(dirname(__FILE__) . '/../app/');
        $targetPath = app_path() . $this->context;

        try {
            $installer = new BundleInstaller($rootPath, $targetPath, self::DEFAULT_CONTEXT, $this->context);
            $installer->copyFiles();
            $installer->registerModules('Acl', 'Auth', 'Users');
            $installer->createSchema(
                "App\\{$this->context}\\Users\Domain\User",
                "App\\{$this->context}\\Acl\Domain\Acl"
            );
        } catch (BundleInstallerException $e) {
            $this->output->writeln("<error>{$e->getMessage()}</error>");
            return 0;
        }

        return 0;
    }
}
