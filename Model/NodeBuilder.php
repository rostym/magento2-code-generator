<?php
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
     * @param string $name
     * @param array  $attributes
     */
    public function __construct($name, array $attributes = [])
    {
        $this->domDocument = new \DOMDocument('1.0', 'UTF-8');
        $this->stack = new \SplStack();
        $this->initRoot($name, $attributes);
    }

    /**
     * @param string $name
     * @param array $attributes
     * @return void
     */
    private function initRoot($name, array $attributes)
    {
        $this->elementNode($name, '', $attributes);
        $this->rootElement = $this->lastCreatedElement;
        $this->children();
    }

    /**
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return $this
     */
    public function elementNode($name, $value = '', array $attributes = [])
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
        $this->elementNode('item', $value, $attributes);

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
        $this->elementNode('argument', $value, $baseAttributes);

        return $this;
    }

    public function assign(NodeBuilder $nodeBuilder)
    {
        if ($this->stack->isEmpty()) {
            return $this;
        }

        /** @var \DOMDocument $currentElement */
        $currentElement = $this->stack->top();
        $currentElement->appendChild($nodeBuilder->getRootElement());

        return $this;
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
