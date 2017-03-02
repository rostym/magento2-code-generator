<?php

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilder;

use Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilderInterface;

/**
 * Interface ArgumentBuilderInterface
 *
 * @package Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilder
 */
interface ArgumentBuilderInterface
{
    /**
     * Value
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function value($value);

    /**
     * Type
     *
     * @param string $type
     *
     * @return $this
     */
    public function type($type);

    /**
     * Passed By Reference
     *
     * @return bool
     */
    public function passedByReference();

    /**
     * Position
     *
     * @param int $position
     *
     * @return $this
     */
    public function position($position);

    /**
     * Build
     *
     * @return object
     */
    public function build();

    /**
     * Finish Building
     *
     * @return MethodBuilderInterface
     */
    public function finishBuilding();
}
