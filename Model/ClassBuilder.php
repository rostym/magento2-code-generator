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
    private $classGeneratorObject;

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

    /**
     * ClassBuilder constructor.
     *
     * @param string $className
     * @param bool   $isInterface
     */
    public function __construct($className, $isInterface = false)
    {
        $this->isInterface = $isInterface;
        $this->initGenerator();
        $this->classGeneratorObject->setName($className);
    }

    /**
     * @inheritdoc
     */
    public function markAsAbstract()
    {
        $this->classGeneratorObject->setAbstract(true);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function markAsFinal()
    {
        $this->classGeneratorObject->setFinal(true);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function extendedFrom($className)
    {
        $this->classGeneratorObject->setExtendedClass($className);

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
        $this->classGeneratorObject->setImplementedInterfaces($this->implementedInterfaces);

        foreach ($this->properties as $property) {
            $this->classGeneratorObject->addPropertyFromGenerator($property->build());
        }

        foreach ($this->methods as $method) {
            $this->classGeneratorObject->addMethodFromGenerator($method->build());
        }

        return $this->classGeneratorObject;
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
     * Initialize generator object
     */
    private function initGenerator()
    {
        if (!$this->isInterface) {
            $this->classGeneratorObject = new ClassGenerator();
            return;
        }

        $this->classGeneratorObject = new InterfaceGenerator();
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
        $this->classGeneratorObject->setNamespaceName($name);

        return $this;
    }
}
