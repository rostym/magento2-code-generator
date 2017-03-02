<?php

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\ClassBuilder;

use Krifollk\CodeGenerator\Api\ClassBuilder\PropertyBuilderInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder;
use Zend\Code\Generator\AbstractMemberGenerator;
use Zend\Code\Generator\PropertyGenerator;

/**
 * Class PropertyBuilder
 *
 * @package Krifollk\CodeGenerator\Model\ClassBuilder
 */
class PropertyBuilder implements PropertyBuilderInterface
{
    /** @var ClassBuilder */
    private $classBuilder;

    /** @var PropertyGenerator */
    private $propertyGeneratorObject;

    /**
     * PropertyBuilder constructor.
     *
     * @param string       $propertyName
     * @param ClassBuilder $classBuilder
     */
    public function __construct($propertyName, ClassBuilder $classBuilder)
    {
        $this->propertyGeneratorObject = new PropertyGenerator($propertyName);
        $this->classBuilder = $classBuilder;
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
     */
    public function defaultValue($value)
    {
        $this->propertyGeneratorObject->setDefaultValue($value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function build()
    {
        return $this->propertyGeneratorObject;
    }

    /**
     * @inheritdoc
     */
    public function markAsPublic()
    {
        $this->propertyGeneratorObject->setVisibility(AbstractMemberGenerator::VISIBILITY_PUBLIC);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function markAsPrivate()
    {
        $this->propertyGeneratorObject->setVisibility(AbstractMemberGenerator::VISIBILITY_PRIVATE);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function markAsProtected()
    {
        $this->propertyGeneratorObject->setVisibility(AbstractMemberGenerator::VISIBILITY_PROTECTED);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function markAsConst()
    {
        $this->propertyGeneratorObject->setConst(true);

        return $this;
    }
}
