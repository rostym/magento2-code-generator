<?php

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\ClassBuilder\MethodBuilder;

use Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilder\ArgumentBuilderInterface;
use Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilderInterface;
use Zend\Code\Generator\ParameterGenerator;

/**
 * Class ArgumentBuilder
 *
 * @package Krifollk\CodeGenerator\Model\ClassBuilder\MethodBuilder
 */
class ArgumentBuilder implements ArgumentBuilderInterface
{
    /** @var ParameterGenerator */
    private $argumentGenerator;

    /** @var MethodBuilderInterface */
    private $methodBuilder;

    /**
     * ArgumentBuilder constructor.
     *
     * @param string                 $name
     * @param MethodBuilderInterface $methodBuilder
     */
    public function __construct($name, $methodBuilder)
    {
        $this->argumentGenerator = new ParameterGenerator();
        $this->argumentGenerator->setName($name);
        $this->methodBuilder = $methodBuilder;
    }

    /**
     * @inheritdoc
     */
    public function value($value)
    {
        $this->argumentGenerator->setDefaultValue($value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function type($type)
    {
        $this->argumentGenerator->setType($type);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function passedByReference()
    {
        $this->argumentGenerator->setPassedByReference(true);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function position($position)
    {
        $this->argumentGenerator->setPosition($position);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function build()
    {
        return $this->argumentGenerator;
    }

    /**
     * @inheritdoc
     */
    public function finishBuilding()
    {
        return $this->methodBuilder;
    }
}