<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\TableInfo;

/**
 * Class Column
 *
 * @package Krifollk\CodeGenerator\Model\TableInfo
 */
class Column
{
    /** @var string */
    private $name         = '';

    /** @var bool */
    private $isPrimary    = false;

    /** @var string */
    private $phpType      = '';

    /** @var string */
    private $originalType = '';

    /**
     * Column constructor.
     *
     * @param string $name
     * @param bool   $isPrimary
     * @param string $phpType
     * @param string $originalType
     */
    public function __construct($name, $isPrimary, $phpType, $originalType)
    {
        $this->name         = (string)$name;
        $this->isPrimary    = (bool)$isPrimary;
        $this->phpType      = (string)$phpType;
        $this->originalType = (string)$originalType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isIsPrimary()
    {
        return $this->isPrimary;
    }

    /**
     * @return string
     */
    public function getPhpType()
    {
        return $this->phpType;
    }

    /**
     * @return string
     */
    public function getOriginalType()
    {
        return $this->originalType;
    }
}
