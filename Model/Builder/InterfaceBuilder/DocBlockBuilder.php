<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder;

use Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder;
use Krifollk\CodeGenerator\Model\GenericTag;
use Zend\Code\Generator\DocBlockGenerator;

/**
 * Class DocBlockBuilder
 *
 * @package Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder
 */
class DocBlockBuilder
{
    /** @var DocBlockGenerator */
    private $generatorObject;

    /** @var InterfaceBuilder */
    private $parentBuilder;

    /**
     * DocBlockBuilder constructor.
     *
     * @param mixed $parentBuilder
     */
    public function __construct($parentBuilder)
    {
        $this->parentBuilder = $parentBuilder;
        $this->generatorObject = new DocBlockGenerator();
    }

    public function disableWordWrap()
    {
        $this->generatorObject->setWordWrap(false);

        return $this;
    }

    public function shortDescription(string $shortDescription)
    {
        $this->generatorObject->setShortDescription($shortDescription);

        return $this;
    }

    public function addTag(string $tagName, string $content)
    {
        $tag = new GenericTag();
        $tag->setName($tagName);
        $tag->setContent($content);
        $this->generatorObject->setTag($tag);

        return $this;
    }

    public function addEmptyLine()
    {
        $this->generatorObject->setTag(new GenericTag());

        return $this;
    }

    public function build()
    {
        return $this->generatorObject;
    }

    public function finishBuilding()
    {
        return $this->parentBuilder;
    }
}
