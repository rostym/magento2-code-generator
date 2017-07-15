<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Console\Command;

use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class AbstractCommand
 *
 * @package Krifollk\CodeGenerator\Console\Command
 */
abstract class AbstractCommand extends Command
{
    const DIR_OPTION = 'dir';
    const MODULE_NAME_ARGUMENT = 'module_name';

    /**
     * Create module name entity
     *
     * @param string $moduleName
     *
     * @return ModuleNameEntity
     * @throws \InvalidArgumentException
     */
    protected function createModuleNameEntity($moduleName): ModuleNameEntity
    {
        return new ModuleNameEntity($moduleName);
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->addOption(
            self::DIR_OPTION,
            null,
            InputOption::VALUE_OPTIONAL,
            'Module directory. Ex: app/module-some-module',
            ''
        );

        $this->addArgument(self::MODULE_NAME_ARGUMENT, InputArgument::REQUIRED, 'Module name');

        parent::configure();
    }

    /**
     * Get Directory option
     *
     * @param InputInterface $input
     *
     * @return string
     */
    protected function getDirOption(InputInterface $input): string
    {
        return trim($input->getOption(self::DIR_OPTION), " \t\n\r \v/\\");
    }
}
