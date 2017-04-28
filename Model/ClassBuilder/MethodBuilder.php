<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\ClassBuilder;

use Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilder\ArgumentBuilderInterface;
use Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilderInterface;
use Krifollk\CodeGenerator\Api\ClassBuilderInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder\MethodBuilder\ArgumentBuilder;
use Magento\Framework\Code\Generator\InterfaceMethodGenerator;
use Zend\Code\Generator\AbstractMemberGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

/**
 * Class MethodBuilder
 *
 * @package Krifollk\CodeGenerator\Model\ClassBuilder
 */
class MethodBuilder implements MethodBuilderInterface
{
    /** @var InterfaceMethodGenerator|MethodGenerator */
    private $methodGenerator;

    /** @var ClassBuilderInterface */
    private $classBuilder;

    /** @var ArgumentBuilderInterface[] */
    private $arguments = [];

    /** @var \Krifollk\CodeGenerator\Model\ClassBuilder\MethodBuilder\DocBlock  */
    private $docBlockBuilder;

    /**
     * MethodBuilder constructor.
     *
     * @param string                $name
     * @param string                $body
     * @param ClassBuilderInterface $classBuilder
     */
    public function __construct($name, $body, ClassBuilderInterface $classBuilder)
    {
        $this->classBuilder = $classBuilder;
        $this->methodGenerator = new MethodGenerator();
        $this->methodGenerator->setName($name);
        $this->methodGenerator->setBody($body);
    }

    /**
     * @inheritdoc
     */
    public function markAsPublic()
    {
        $this->methodGenerator->setVisibility(AbstractMemberGenerator::VISIBILITY_PUBLIC);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function markAsPrivate()
    {
        $this->methodGenerator->setVisibility(AbstractMemberGenerator::VISIBILITY_PRIVATE);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function markAsProtected()
    {
        $this->methodGenerator->setVisibility(AbstractMemberGenerator::VISIBILITY_PROTECTED);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function markAsAbstract()
    {
        $this->methodGenerator->setAbstract(true);

        return $this;
    }

    public function withBody(string $content)
    {
        $this->methodGenerator->setBody($content);

        return $this;
    }

    public function startDocBlockBuilding()
    {
        $this->docBlockBuilder = new \Krifollk\CodeGenerator\Model\ClassBuilder\MethodBuilder\DocBlock($this);

        return $this->docBlockBuilder;
    }

    public function addArgument(string $name, string $type = '', $value = '__null__')
    {
        $argument = new ParameterGenerator();
        $argument->setName($name)->setType($type);

        if ($value !== '__null__') {
            $argument->setDefaultValue($value);
        }

        $this->methodGenerator->setParameter($argument);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function finishBuilding()
    {
        return $this->classBuilder;
    }

    /**
     * @inheritdoc
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    public function build()
    {
        if ($this->docBlockBuilder) {
            $this->methodGenerator->setDocBlock($this->docBlockBuilder->build());
        }

        foreach ($this->arguments as $argument) {
            $this->methodGenerator->setParameter($argument->build());
        }

        return $this->methodGenerator;
    }

    /**
     * @inheritdoc
     */
    public function startArgumentBuilding($name)
    {
        $argumentBuilder = new ArgumentBuilder($name, $this);
        $this->arguments[$name] = $argumentBuilder;

        return $argumentBuilder;
    }
}
