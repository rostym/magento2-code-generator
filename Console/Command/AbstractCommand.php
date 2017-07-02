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

/**
 * Class AbstractCommand
 *
 * @package Krifollk\CodeGenerator\Console\Command
 */
abstract class AbstractCommand extends Command
{
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
}
