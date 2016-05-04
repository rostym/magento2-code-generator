<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Api;

/**
 * Interface GeneratorInterface
 *
 * @package Krifollk\CodeGenerator\Api
 */
interface GeneratorInterface
{
    /**
     * Generate entity
     *
     * @return GeneratorResultInterface
     */
    public function generate();
}