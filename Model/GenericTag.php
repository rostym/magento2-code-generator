<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model;

/**
 * Class GenericTag
 *
 * Added possibility to render empty line instead tag
 *
 * @package Krifollk\CodeGenerator\Model
 */
class GenericTag extends \Zend\Code\Generator\DocBlock\Tag\GenericTag
{
    /**
     * Generate tag or empty line
     *
     * @return string
     */
    public function generate()
    {
        if ($this->name === null && $this->content === null) {
            return '';
        }

        return parent::generate();
    }
}
