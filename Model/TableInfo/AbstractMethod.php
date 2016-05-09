<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\TableInfo;

use Zend\Code\Generator\DocBlockGenerator;

/**
 * Class Method
 *
 * @package Krifollk\CodeGenerator\Model\TableInfo\Column
 */
abstract class AbstractMethod
{
    /**
     * Constant name
     *
     * @var string
     */
    protected $constName = '';

    /**
     * Method name
     *
     * @var string
     */
    protected $name = '';

    /**
     * @var DocBlockGenerator
     */
    protected $docBlock;

    /**
     * AbstractMethod constructor.
     *
     * @param string            $constName
     * @param DocBlockGenerator $docBlock
     */
    public function __construct($constName, DocBlockGenerator $docBlock)
    {
        $this->constName = $constName;
        $this->docBlock = $docBlock;
    }

    /**
     * @return DocBlockGenerator
     */
    public function getDocBlock()
    {
        return $this->docBlock;
    }

    /**
     * Get method name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get method body content
     *
     * @return string
     */
    abstract public function getBody();
}
