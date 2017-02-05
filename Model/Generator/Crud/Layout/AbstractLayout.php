<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Layout;

use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;

/**
 * Class AbstractLayout
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Layout
 */
abstract class AbstractLayout extends AbstractGenerator
{
    /**
     * Generate page element
     *
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    protected function generatePage(\DOMDocument $document)
    {
        $page = $document->createElement('page');
        $page->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $page->setAttribute(
            'xsi:noNamespaceSchemaLocation',
            'urn:magento:framework:View/Layout/etc/page_configuration.xsd'
        );

        return $page;
    }
}
