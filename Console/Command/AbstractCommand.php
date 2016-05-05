<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Console\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractCommand
 *
 * @package Krifollk\CodeGenerator\Console\Command
 */
abstract class AbstractCommand extends Command
{
    /**
     * @param string $moduleName
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function validateModuleName($moduleName)
    {
        if (!preg_match('/[A-Z]+[A-Za-z0-9]+\/[A-Z]+[A-Z0-9a-z]+/', $moduleName)) {
            throw new InvalidArgumentException('Wrong module name. Example: Test/Module');
        }

        return true;
    }
}
