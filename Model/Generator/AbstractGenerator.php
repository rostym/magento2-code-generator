<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator;

use Krifollk\CodeGenerator\Api\GeneratorInterface;
use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class AbstractGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    /**
     * Directory separator
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * @inheritdoc
     * @throws \InvalidArgumentException
     */
    public function generate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $this->checkArguments($additionalArguments);

        return $this->internalGenerate($moduleNameEntity, $additionalArguments);
    }

    /**
     * Checks that all required arguments passed
     *
     * @param array $arguments
     *
     * @throws \InvalidArgumentException
     */
    protected function checkArguments(array $arguments)
    {
        foreach ($this->requiredArguments() as $requiredArgument) {
            if (array_key_exists($requiredArgument, $arguments)) {
                continue;
            }

            throw new \InvalidArgumentException(sprintf('{%s} is required. [%s]', $requiredArgument, get_class($this)));
        }
    }

    /**
     * Return array of required arguments
     *
     * @return array
     */
    abstract protected function requiredArguments(): array;

    /**
     * @param ModuleNameEntity $moduleNameEntity
     * @param array            $additionalArguments
     *
     * @return GeneratorResultInterface
     * @internal param array $arguments
     *
     */
    abstract protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface;
}
