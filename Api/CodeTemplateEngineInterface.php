<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Api;

/**
 * Class Engine
 *
 * @package Krifollk\CodeGenerator\Model\CodeTemplate
 */
interface CodeTemplateEngineInterface
{
    /**
     * Render php code template
     *
     * @param string $template
     * @param array  $variables
     *
     * @return string
     */
    public function render(string $template, array $variables = []): string;
}
