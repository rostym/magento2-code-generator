<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\TableInfo;

/**
 * Class Constant
 *
 * @package Krifollk\CodeGenerator\Model\TableInfo
 */
class Constant
{
    /**
     * Constant name
     *
     * @var string
     */
    private $name = '';

    /**
     * Constant value
     *
     * @var mixed
     */
    private $value;

    /**
     * Constant constructor.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Get constant name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get constant value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
