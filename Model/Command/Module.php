<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command;

use Krifollk\CodeGenerator\Api\ModulesDirProviderInterface;
use Krifollk\CodeGenerator\Model\Generator\Module\ModuleXml;
use Krifollk\CodeGenerator\Model\Generator\Module\Registration;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Class Module
 *
 * @package Krifollk\CodeGenerator\Model\Command
 */
class Module extends AbstractCommand
{
    /** @var Registration */
    private $registration;

    /** @var ModuleXml */
    private $moduleXml;

    /**
     * Module constructor.
     *
     * @param Registration                $registration
     * @param ModuleXml                   $moduleXml
     * @param File                        $file
     * @param ModulesDirProviderInterface $modulesDirProvider
     */
    public function __construct(
        Registration $registration,
        ModuleXml $moduleXml,
        File $file,
        ModulesDirProviderInterface $modulesDirProvider
    ) {
        $this->registration = $registration;
        $this->moduleXml = $moduleXml;
        parent::__construct($file, $modulesDirProvider);
    }

    /**
     * Generate base module files
     *
     * @param ModuleNameEntity $moduleNameEntity
     * @param string           $version
     *
     * @return \Generator
     * @throws \InvalidArgumentException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function generate(ModuleNameEntity $moduleNameEntity, $version = ''): \Generator
    {
        $container = $this->createResultContainer();

        $container->insert('registration', $this->registration->generate($moduleNameEntity));
        $container->insert(
            'module_xml',
            $this->moduleXml->generate($moduleNameEntity, ['moduleName' => $moduleNameEntity, 'version' => $version])
        );

        return $this->generateFiles($container);
    }
}
