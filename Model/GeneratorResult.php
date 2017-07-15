<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;

/**
 * Class GeneratorResult
 *
 * @package Krifollk\CodeGenerator\Model
 */
class GeneratorResult implements GeneratorResultInterface
{
    /** @var string */
    private $content;

    /** @var string */
    private $destinationFile;

    /** @var string */
    private $entityName;

    /** @var array */
    private $exposedMessages;

    /**
     * GeneratorResult constructor.
     *
     * @param string $content
     * @param string $destinationFile
     * @param string $entityName
     * @param array  $exposedMessages
     */
    public function __construct(
        string $content,
        string $destinationFile,
        string $entityName = '',
        array $exposedMessages = []
    ) {
        $this->content = $content;
        $this->destinationFile = $destinationFile;
        $this->entityName = $entityName;
        $this->exposedMessages = $exposedMessages;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDestinationDir(): string
    {
        return dirname($this->getDestinationFile());
    }

    public function getDestinationFile(): string
    {
        return $this->destinationFile;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getExposedMessages(): array
    {
        return $this->exposedMessages;
    }
}
