<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Api\ClassBuilder;

use Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilder\ArgumentBuilderInterface;
use Krifollk\CodeGenerator\Api\ClassBuilderInterface;

/**
 * Interface MethodBuilderInterface
 *
 * @package Krifollk\CodeGenerator\Api\ClassBuilder
 */
interface MethodBuilderInterface
{
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
     * Mark As Abstract
     *
     * @return $this
     */
    public function markAsAbstract();

    /**
     * Start Argument Building
     *
     * @param string $name
     *
     * @return ArgumentBuilderInterface
     */
    public function startArgumentBuilding($name);

    /**
     * Finish
     *
     * @return ClassBuilderInterface
     */
    public function finishBuilding();

    /**
     * @param string $content
     *
     * @return $this
     */
    public function withBody(string $content);

    /**
     * @return \Krifollk\CodeGenerator\Model\ClassBuilder\MethodBuilder\DocBlock
     */
    public function startDocBlockBuilding();

    /**
     * @return $this
     */
    public function addArgument(string $name, string $type = '', $value = '__null__');

    /**
     * Build
     *
     * @return object
     */
    public function build();
}
