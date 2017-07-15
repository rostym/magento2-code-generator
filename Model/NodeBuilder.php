<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model;

/**
 * Class NodeBuilder
 *
 * @package Krifollk\CodeGenerator\Model
 */
class NodeBuilder
{
    /** @var \DOMDocument */
    private $domDocument;

    /** @var \SplStack */
    private $stack;

    /** @var \DOMElement */
    private $rootElement;

    /** @var \DOMElement */
    private $lastCreatedElement;

    /**
     * NodeBuilder constructor.
     *
     * @param string            $name
     * @param array             $attributes
     * @param \DOMDocument|null $document
     */
    public function __construct($name, array $attributes = [], \DOMDocument $document = null)
    {
        $this->stack = new \SplStack();
        if ($document === null) {
            $this->initnew($name, $attributes);
            return;
        }

        $this->domDocument = $document;
        $this->lastCreatedElement = $document->documentElement;
        $this->rootElement = $this->lastCreatedElement;
        $this->children();
    }

    /**
     * @param string $name
     * @param array $attributes
     * @return void
     */
    private function initnew($name, array $attributes)
    {
        $this->domDocument = new \DOMDocument('1.0', 'UTF-8');
        $this->elementNode($name, $attributes, '');
        $this->rootElement = $this->lastCreatedElement;
        $this->children();
    }

    /**
     * @param string $name
     * @param array  $attributes
     *
     * @param string $value
     *
     * @return $this
     */
    public function elementNode($name, array $attributes = [], $value = '')
    {
        $element = $this->domDocument->createElement($name);

        foreach ($attributes as $attributeName => $attributeValue) {
            $element->setAttribute($attributeName, $attributeValue);
        }

        if ($value !== '') {
            $element->nodeValue = $value;
        }

        $this->lastCreatedElement = $element;

        if ($this->stack->isEmpty()) {
            return $this;
        }

        /** @var \DOMDocument $currentElement */
        $currentElement = $this->stack->top();
        $currentElement->appendChild($element);

        return $this;
    }

    /**
     * @return $this
     */
    public function children()
    {
        $this->stack->push($this->lastCreatedElement);

        return $this;
    }

    /**
     * @return $this
     */
    public function endNode()
    {
        $this->stack->pop();

        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $value
     * @param array  $additionalAttributes
     *
     * @return $this
     */
    public function itemNode($name, $type, $value = '', array $additionalAttributes = [])
    {
        $baseAttributes = ['name' => $name, 'xsi:type' => $type];
        $attributes = array_merge($baseAttributes, $additionalAttributes);
        $this->elementNode('item', $attributes, $value);

        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $value
     *
     * @return $this
     */
    public function argumentNode($name, $type, $value = '')
    {
        $baseAttributes = ['name' => $name, 'xsi:type' => $type];
        $this->elementNode('argument', $baseAttributes, $value);

        return $this;
    }

    public function trySetPointerToElement(string $query): bool
    {
        $xpath = new \DOMXPath($this->domDocument);
        $result = $xpath->query($query);

        if ($result->length === 0) {
            return false;
        }

        $this->lastCreatedElement = $result->item(0);
        $this->children();

        return true;
    }

    public function isExistByPath(string $path): bool
    {
        $xpath = new \DOMXPath($this->domDocument);
        $result = $xpath->query($path);

        if ($result->length === 0) {
            return false;
        }

        return true;
    }

    public function getRootElement()
    {
        return $this->rootElement;
    }

    /**
     * @return string
     */
    public function toXml()
    {
        $this->domDocument->appendChild($this->rootElement);
        $this->domDocument->formatOutput = true;

        return $this->domDocument->saveXML();
    }
}
