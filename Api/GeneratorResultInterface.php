<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Api;

/**
 * Interface GeneratorResultInterface
 *
 * @package Krifollk\CodeGenerator\Api
 */
interface GeneratorResultInterface
{
    /**
     * Get content
     *
     * @return string
     */
    public function getContent();

    /**
     * Get destination dir
     *
     * @return string
     */
    public function getDestinationDir();

    /**
     * Get destination path
     *
     * @return string
     */
    public function getDestinationFile();

    /**
     * Get entity name
     *
     * @return string
     */
    public function getEntityName();
}
