<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Api;

use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Interface GeneratorInterface
 *
 * @package Krifollk\CodeGenerator\Api
 */
interface GeneratorInterface
{
    /**
     * Generate entity
     *
     * @param ModuleNameEntity $moduleNameEntity
     * @param array            $additionalArguments
     *
     * @return GeneratorResultInterface
     */
    public function generate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface;
}
