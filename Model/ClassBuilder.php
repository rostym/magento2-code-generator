<?php

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model;

use Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilderInterface;
use Krifollk\CodeGenerator\Api\ClassBuilder\PropertyBuilderInterface;
use Krifollk\CodeGenerator\Api\ClassBuilderInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder\DocBlock;
use Krifollk\CodeGenerator\Model\ClassBuilder\MethodBuilder;
use Krifollk\CodeGenerator\Model\ClassBuilder\PropertyBuilder;
use Magento\Framework\Code\Generator\ClassGenerator;

/**
 * Class ClassBuilder
 *
 * @package Krifollk\CodeGenerator\Model
 */
class ClassBuilder implements ClassBuilderInterface
{
    /** @var ClassGenerator */
    private $generatorObject;

    /** @var bool */
    private $autoResolvingNamespaces = false;

    /** @var string[] */
    private $implementedInterfaces = [];

    /** @var PropertyBuilderInterface[] */
    private $properties = [];

    /** @var MethodBuilderInterface[] */
    private $methods = [];

    /** @var bool */
    private $isInterface;

    /** @var DocBlock */
    private $docBlockBuilder;

    /**
     * ClassBuilder constructor.
     *
     * @param string $className
     * @param bool   $isInterface
     */
    public function __construct($className, $isInterface = false)
    {
        $this->isInterface = $isInterface;
        $this->generatorObject = new ClassGenerator();
        $this->generatorObject->setName($className);
    }

    public function startDocBlockBuilding()
    {
        $this->docBlockBuilder = new DocBlock($this);

        return $this->docBlockBuilder;
    }

    /**
     * @inheritdoc
     */
    public function markAsAbstract()
    {
        $this->generatorObject->setAbstract(true);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function markAsFinal()
    {
        $this->generatorObject->setFinal(true);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function extendedFrom($className)
    {
        $this->generatorObject->setExtendedClass($className);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function implementsInterface($interfaceName)
    {
        $this->implementedInterfaces[] = $interfaceName;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function startPropertyBuilding($propertyName)
    {
        $propertyBuilder = new PropertyBuilder($propertyName, $this);
        $this->properties[$propertyName] = $propertyBuilder;

        return $propertyBuilder;
    }

    /**
     * @inheritdoc
     */
    public function resolveNamespacesAutomatically()
    {
        $this->autoResolvingNamespaces = true;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isEnabledAutoResolvingNamespaces()
    {
        return $this->autoResolvingNamespaces;
    }

    /**
     * @inheritdoc
     * @throws \InvalidArgumentException
     */
    public function build()
    {
        if ($this->implementedInterfaces) {
            $this->generatorObject->setImplementedInterfaces($this->implementedInterfaces);
        }

        if ($this->docBlockBuilder) {
            $this->generatorObject->setDocBlock($this->docBlockBuilder->build());
        }

        foreach ($this->properties as $property) {
            $this->generatorObject->addPropertyFromGenerator($property->build());
        }

        foreach ($this->methods as $method) {
            $this->generatorObject->addMethodFromGenerator($method->build());
        }

        return $this->generatorObject;
    }

    /**
     * Start Method Generation
     *
     * @param string $name
     * @param string $body
     *
     * @return MethodBuilderInterface
     */
    public function startMethodBuilding($name, $body = '')
    {
        $methodBuilder = new MethodBuilder($name, $body, $this);
        $this->methods[$name] = $methodBuilder;

        return $methodBuilder;
    }

    /**
     * Is Interface
     *
     * @return bool
     */
    public function isInterface()
    {
        return $this->isInterface;
    }

    /**
     * Uses Namespace
     *
     * @param string $name
     *
     * @return $this
     */
    public function usesNamespace($name)
    {
        $this->generatorObject->setNamespaceName($name);

        return $this;
    }

    public function addConstant(string $name, string $value)
    {
        $this->generatorObject->addConstant($name, $value);

        return $this;
    }

    public function addUse($use)
    {
        $this->generatorObject->addUse($use);

        return $this;
    }
}
