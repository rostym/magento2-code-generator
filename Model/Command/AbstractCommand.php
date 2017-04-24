<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command;

use Krifollk\CodeGenerator\Model\GeneratorResult\Container;
use Magento\Framework\Filesystem\Driver\File;
use Krifollk\CodeGenerator\Model\GeneratorResult;

/**
 * Class AbstractCommand
 *
 * @package Krifollk\CodeGenerator\Model\Command
 */
class AbstractCommand
{
    /** @var File */
    private $file;

    /**
     * AbstractCommand constructor.
     *
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Generate files
     *
     * @param Container $container
     *
     * @return \Generator
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function generateFiles(Container $container)
    {
        /** @var GeneratorResult $entity */
        foreach ($container->getAll() as $entity) {
            $absoluteFilePath = $this->getModulesDir() . $entity->getDestinationFile();
            $absoluteDir = dirname($absoluteFilePath);
            $this->file->createDirectory($absoluteDir);
            $this->file->filePutContents($absoluteFilePath, $entity->getContent());

            yield $absoluteFilePath;
        }
    }

    /**
     * @return Container
     */
    protected function createResultContainer(): Container
    {
        return new Container();
    }

    private function getModulesDir(): string
    {
        return BP . '/app/code/';
    }
}
