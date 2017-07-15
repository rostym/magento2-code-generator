<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\GeneratorResult;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;

/**
 * Class Container
 *
 * @package Krifollk\CodeGenerator\Model\GeneratorResult
 */
class Container
{
    /** @var GeneratorResultInterface[] */
    private $container = [];

    /**
     * Insert result to container
     *
     * @param string                   $name
     * @param GeneratorResultInterface $generatorResult
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function insert(string $name, GeneratorResultInterface $generatorResult)
    {
        if (isset($this->container[$name])) {
            throw new \InvalidArgumentException(sprintf('{%s} result already exists.', $name));
        }

        $this->container[$name] = $generatorResult;
    }

    /**
     * Get result from container by name
     *
     * @param string $name
     *
     * @return GeneratorResultInterface
     * @throws \InvalidArgumentException
     */
    public function get(string $name): GeneratorResultInterface
    {
        if (!isset($this->container[$name])) {
            throw new \InvalidArgumentException(sprintf('{%s} result not found.', $name));
        }

        return $this->container[$name];
    }

    /**
     * Get all results
     *
     * @return GeneratorResultInterface[]
     */
    public function getAll(): array
    {
        return $this->container;
    }
}
