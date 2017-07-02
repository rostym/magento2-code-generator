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
use Krifollk\CodeGenerator\Model\Generator\Module\ComposerJsonGenerator;
use Krifollk\CodeGenerator\Model\Generator\Module\InstallDataGenerator;
use Krifollk\CodeGenerator\Model\Generator\Module\InstallSchemaGenerator;
use Krifollk\CodeGenerator\Model\Generator\Module\ModuleXml;
use Krifollk\CodeGenerator\Model\Generator\Module\Registration;
use Krifollk\CodeGenerator\Model\Generator\Module\UninstallGenerator;
use Krifollk\CodeGenerator\Model\Generator\Module\UpgradeDataGenerator;
use Krifollk\CodeGenerator\Model\Generator\Module\UpgradeSchemaGenerator;
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

    /** @var ComposerJsonGenerator */
    private $composerJsonGenerator;

    /** @var InstallDataGenerator */
    private $installDataGenerator;

    /** @var InstallSchemaGenerator */
    private $installSchemaGenerator;

    /** @var UninstallGenerator */
    private $uninstallGenerator;

    /** @var UpgradeDataGenerator */
    private $upgradeDataGenerator;

    /** @var UpgradeSchemaGenerator */
    private $upgradeSchemaGenerator;

    /**
     * Module constructor.
     *
     * @param Registration                $registration
     * @param ModuleXml                   $moduleXml
     * @param File                        $file
     * @param ModulesDirProviderInterface $modulesDirProvider
     * @param ComposerJsonGenerator       $composerJsonGenerator
     * @param InstallDataGenerator        $installDataGenerator
     * @param InstallSchemaGenerator      $installSchemaGenerator
     * @param UninstallGenerator          $uninstallGenerator
     * @param UpgradeDataGenerator        $upgradeDataGenerator
     * @param UpgradeSchemaGenerator      $upgradeSchemaGenerator
     */
    public function __construct(
        Registration $registration,
        ModuleXml $moduleXml,
        File $file,
        ModulesDirProviderInterface $modulesDirProvider,
        ComposerJsonGenerator $composerJsonGenerator,
        InstallDataGenerator $installDataGenerator,
        InstallSchemaGenerator $installSchemaGenerator,
        UninstallGenerator $uninstallGenerator,
        UpgradeDataGenerator $upgradeDataGenerator,
        UpgradeSchemaGenerator $upgradeSchemaGenerator
    ) {
        $this->registration = $registration;
        $this->moduleXml = $moduleXml;
        $this->composerJsonGenerator = $composerJsonGenerator;
        $this->installDataGenerator = $installDataGenerator;
        $this->installSchemaGenerator = $installSchemaGenerator;
        $this->uninstallGenerator = $uninstallGenerator;
        $this->upgradeDataGenerator = $upgradeDataGenerator;
        $this->upgradeSchemaGenerator = $upgradeSchemaGenerator;
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

        $container->insert(
            'composer_json',
            $this->composerJsonGenerator->generate($moduleNameEntity, ['version' => $version])
        );

        $container->insert(
            'install_data',
            $this->installDataGenerator->generate($moduleNameEntity)
        );

        $container->insert(
            'install_schema',
            $this->installSchemaGenerator->generate($moduleNameEntity)
        );

        $container->insert(
            'uninstall',
            $this->uninstallGenerator->generate($moduleNameEntity)
        );

        $container->insert(
            'upgrade_data',
            $this->upgradeDataGenerator->generate($moduleNameEntity)
        );

        $container->insert(
            'upgrade_schema',
            $this->upgradeSchemaGenerator->generate($moduleNameEntity)
        );

        return $this->generateFiles($container);
    }
}
