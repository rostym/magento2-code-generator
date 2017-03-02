<?php

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Api\ClassBuilder;

use Krifollk\CodeGenerator\Api\ClassBuilderInterface;

/**
 * Interface PropertyBuilderInterface
 *
 * @package Krifollk\CodeGenerator\Api\ClassBuilder
 */
interface PropertyBuilderInterface
{
    /**
     * Finish Property Generation
     *
     * @return ClassBuilderInterface
     */
    public function finishBuilding();

    /**
     * Default Value
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function defaultValue($value);

    /**
     * Mark As Public
     *
     * @return $this
     */
    public function markAsPublic();

    /**
     * Mark As Private
     *
     * @return $this
     */
    public function markAsPrivate();

    /**
     * Mark As Protected
     *
     * @return $this
     */
    public function markAsProtected();

    /**
     * Mark As Const
     *
     * @return $this
     */
    public function markAsConst();

    /**
     * Build
     *
     * @return object
     */
    public function build();
}
