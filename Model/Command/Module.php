<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command;

use Krifollk\CodeGenerator\Model\Generator\Module\ModuleXmlFactory;
use Krifollk\CodeGenerator\Model\Generator\Module\RegistrationFactory;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Magento\Framework\Filesystem\DriverInterface;

/**
 * Class Module
 *
 * @package Krifollk\CodeGenerator\Model\Command
 */
class Module
{
    /**
     * @var RegistrationFactory
     */
    private $registrationFactory;

    /**
     * @var ModuleXmlFactory
     */
    private $moduleXmlFactory;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $file;

    /**
     * Module constructor.
     *
     * @param RegistrationFactory                       $registrationFactory
     * @param ModuleXmlFactory                          $moduleXmlFactory
     * @param \Magento\Framework\Filesystem\Driver\File $file
     */
    public function __construct(
        RegistrationFactory $registrationFactory,
        ModuleXmlFactory $moduleXmlFactory,
        \Magento\Framework\Filesystem\Driver\File $file
    ) {
        $this->registrationFactory = $registrationFactory;
        $this->moduleXmlFactory = $moduleXmlFactory;
        $this->file = $file;
    }

    /**
     * @param string $moduleName
     * @param string $version
     *
     * @return \Generator
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function generate($moduleName, $version = '')
    {
        /** @var GeneratorResult[] $entities */
        $entities = [];

        $entities['registration'] = $this->createRegistrationGenerator($moduleName)->generate();
        $entities['moduleXml'] = $this->createModuleXmlGenerator($moduleName, $version)->generate();

        return $this->generateFiles($entities);
    }

    /**
     * @param string $moduleName
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Module\Registration
     */
    protected function createRegistrationGenerator($moduleName)
    {
        return $this->registrationFactory->create(['moduleName' => $moduleName]);
    }

    /**
     * @param string $moduleName
     * @param string $version
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Module\ModuleXml
     */
    protected function createModuleXmlGenerator($moduleName, $version = '')
    {
        return $this->moduleXmlFactory->create(['moduleName' => $moduleName, 'version' => $version]);
    }

    /**
     * @param array $entities
     *
     * @todo move this code to abstract command class
     * @return \Generator
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function generateFiles(array $entities)
    {
        /** @var GeneratorResult $entity */
        foreach ($entities as $entity) {
            $this->file->createDirectory($entity->getDestinationDir(), DriverInterface::WRITEABLE_DIRECTORY_MODE);
            $this->file->filePutContents($entity->getDestinationFile(), $entity->getContent());
            yield $entity->getDestinationFile();
        }
    }
}
