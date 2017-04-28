<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder;

use Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder;
use Magento\Framework\Code\Generator\InterfaceMethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

/**
 * Class MethodBuilder
 *
 * @package Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder
 */
class MethodBuilder
{
    /** @var InterfaceBuilder */
    private $interfaceBuilder;

    /** @var  InterfaceMethodGenerator */
    private $generatorObject;

    /** @var InterfaceBuilder\MethodBuilder\DocBlock */
    private $docBlockBuilder;

    /**
     * MethodBuilder constructor.
     *
     * @param string           $name
     * @param InterfaceBuilder $interfaceBuilder
     */
    public function __construct(string $name, InterfaceBuilder $interfaceBuilder)
    {
        $this->interfaceBuilder = $interfaceBuilder;
        $this->generatorObject = new InterfaceMethodGenerator();
        $this->generatorObject->setName($name);
    }

    public function addArgument(string $name, string $type = '', $value = '__null__')
    {
        $argument = new ParameterGenerator();
        $argument->setName($name)->setType($type);

        if ($value !== '__null__') {
            $argument->setDefaultValue($value);
        }

        $this->generatorObject->setParameter($argument);

        return $this;
    }

    public function startDocBlockBuilding()
    {
        $this->docBlockBuilder = new InterfaceBuilder\MethodBuilder\DocBlock($this);

        return $this->docBlockBuilder;
    }

    public function build()
    {
        if ($this->docBlockBuilder) {
            $this->generatorObject->setDocBlock($this->docBlockBuilder->build());
        }

        return $this->generatorObject;
    }

    public function finishBuilding()
    {
        return $this->interfaceBuilder;
    }
}
