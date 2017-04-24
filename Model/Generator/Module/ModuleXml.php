<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Module;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\NodeBuilder;

/**
 * Class ModuleXml
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Module
 */
class ModuleXml extends AbstractGenerator
{
    const FILE = '%s/etc/module.xml';
    const DEFAULT_VERSION = '0.1.0';

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['version'];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $version = $additionalArguments['version'] ?? self::DEFAULT_VERSION;

        return new GeneratorResult(
            $this->generateContent($moduleNameEntity, $version),
            $this->generateFilePath($moduleNameEntity)
        );
    }

    /**
     * Generate module.xml content
     *
     * @param ModuleNameEntity $moduleNameEntity
     * @param string           $version
     *
     * @return string
     */
    protected function generateContent(ModuleNameEntity $moduleNameEntity, string $version): string
    {
        $moduleName = $moduleNameEntity->value();

        $nodeBuilder = (new NodeBuilder('config', [
                'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:noNamespaceSchemaLocation' => 'urn:magento:framework:Module/etc/module.xsd'
            ]
        ))->elementNode('module', ['name' => $moduleName, 'setup_version' => $version]);

        return $nodeBuilder->toXml();
    }

    /**
     * Generate file path
     *
     * @param ModuleNameEntity $moduleNameEntity
     *
     * @return string
     */
    protected function generateFilePath(ModuleNameEntity $moduleNameEntity): string
    {
        return sprintf(self::FILE, $moduleNameEntity->asPartOfPath());
    }
}
