<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Api;

use Krifollk\CodeGenerator\Api\ClassBuilder\MethodBuilderInterface;
use Krifollk\CodeGenerator\Api\ClassBuilder\PropertyBuilderInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder\DocBlock;

/**
 * Interface ClassBuilderInterface
 *
 * @package Krifollk\CodeGenerator\Api
 */
interface ClassBuilderInterface
{
    const VISIBILITY_PUBLIC    = 'public';
    const VISIBILITY_PROTECTED = 'protected';
    const VISIBILITY_PRIVATE   = 'protected';

    /**
     * Mark As Abstract
     *
     * @return $this
     */
    public function markAsAbstract();

    /**
     * Mark As Final
     *
     * @return $this
     */
    public function markAsFinal();

    /**
     * Extended From
     *
     * @param string $className
     *
     * @return $this
     */
    public function extendedFrom($className);

    /**
     * Implements interface
     *
     * @param string $interfaceName
     *
     * @return $this
     */
    public function implementsInterface($interfaceName);

    /**
     * Start property generation
     *
     * @param string $propertyName
     *
     * @return PropertyBuilderInterface
     */
    public function startPropertyBuilding($propertyName);

    /**
     * Start Method Generation
     *
     * @param string $name
     * @param string $body
     *
     * @return MethodBuilderInterface
     */
    public function startMethodBuilding($name, $body = '');

    /**
     * Resolve namespaces automatically
     *
     * @return $this
     */
    public function resolveNamespacesAutomatically();

    /**
     * Is enabled auto resolving namespaces
     *
     * @return bool
     */
    public function isEnabledAutoResolvingNamespaces();

    /**
     * Uses Namespace
     *
     * @param string $name
     *
     * @return $this
     */
    public function usesNamespace($name);

    /**
     * @return DocBlock
     */
    public function startDocBlockBuilding();

    /**
     * Build
     *
     * @return object
     */
    public function build();
}
