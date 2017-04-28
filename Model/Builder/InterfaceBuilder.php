<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Builder;

use Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder\DocBlockBuilder;
use Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder\MethodBuilder;
use Krifollk\CodeGenerator\Model\InterfaceGenerator;

/**
 * Class InterfaceBuilder
 *
 * @package Krifollk\CodeGenerator\Model\Builder
 */
class InterfaceBuilder
{
    /** @var InterfaceGenerator */
    private $generatorObject;

    /** @var MethodBuilder[] */
    private $methods = [];

    /** @var DocBlockBuilder */
    private $docBlockbuilder;

    /**
     * InterfaceBuilder constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->generatorObject = new InterfaceGenerator();
        $this->generatorObject->setName($name);
    }

    public function extendedFrom($className)
    {
        $this->generatorObject->setExtendedClass($className);

        return $this;
    }

    public function startMethodBuilding($methodName)
    {
        $methodBuilder = new MethodBuilder($methodName, $this);
        $this->methods[$methodName] = $methodBuilder;

        return $methodBuilder;
    }

    public function startDocBlockBuilding()
    {
        $this->docBlockbuilder = new DocBlockBuilder($this);

        return $this->docBlockbuilder;
    }

    public function build()
    {
        $this->generatorObject->setDocBlock($this->docBlockbuilder->build());

        foreach ($this->methods as $method) {
            $this->generatorObject->addMethodFromGenerator($method->build());
        }

        return $this->generatorObject;
    }

    public function addConstant(string $name, $value)
    {
        $this->generatorObject->addConstant($name, $value);

        return $this;
    }

    public function addUse(string $use)
    {
        $this->generatorObject->addUse($use);

        return $this;
    }
}
