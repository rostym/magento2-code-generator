<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
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
    /**
     * File Content
     *
     * @var string
     */
    private $content = '';

    /**
     * Destination file
     *
     * @var string
     */
    private $destinationFile = '';

    /**
     * Entity name
     *
     * @var string
     */
    private $entityName = '';

    /**
     * GeneratorResult constructor.
     *
     * @param string $content
     * @param string $destinationFile
     * @param string $entityName
     */
    public function __construct($content, $destinationFile, $entityName)
    {
        $this->content = $content;
        $this->destinationFile = $destinationFile;
        $this->entityName = $entityName;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get destination dir
     *
     * @return string
     */
    public function getDestinationDir()
    {
        return dirname($this->getDestinationFile());
    }

    /**
     * Get destination file
     *
     * @return string
     */
    public function getDestinationFile()
    {
        return $this->destinationFile;
    }

    /**
     * Get entity name
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }
}
