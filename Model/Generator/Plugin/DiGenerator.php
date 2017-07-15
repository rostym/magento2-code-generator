<?php

declare(strict_types=1);

namespace Krifollk\CodeGenerator\Model\Generator\Plugin;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractXmlGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\NodeBuilder;

/**
 * Class DiGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Plugin
 */
class DiGenerator extends AbstractXmlGenerator
{
    const TYPE_XPATH = "//config/type[contains(@name,'%s')]";

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['interceptedClassName', 'pluginClassName'];
    }

    /**
     * @inheritdoc
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $interceptedClassName = trim($additionalArguments['interceptedClassName'], '\\');
        $pluginClassName = trim($additionalArguments['pluginClassName'], '\\');
        $sortOrder = (string)($additionalArguments['sortOrder'] ?? '0');
        $pluginName = $additionalArguments['pluginName'] ?? strtolower(str_replace('\\', '_', $pluginClassName));
        $scope = $additionalArguments['scope'] ?? '';

        $file = $this->modulesDirProvider->getModulesDir() . $this->getDiConfigFile($moduleNameEntity, $scope);

        if ($this->file->isExists($file)) {
            $domDocument = $this->load($file);

            $nodeBuilder = new NodeBuilder('', [], $domDocument);

            if (!$nodeBuilder->isExistByPath(sprintf(self::TYPE_XPATH, $interceptedClassName))) {
                $nodeBuilder
                    ->elementNode('type', ['name' => $interceptedClassName])->children()
                        ->elementNode('plugin', ['name' => $pluginName, 'type' => $pluginClassName, 'sortOrder' => $sortOrder])
                    ->endNode();
            } else {
                $nodeBuilder->trySetPointerToElement(sprintf(self::TYPE_XPATH, $interceptedClassName));
                $nodeBuilder->elementNode('plugin', [
                        'name'      => $pluginName,
                        'type'      => $pluginClassName,
                        'sortOrder' => $sortOrder
                    ]
                );
            }

        } else {
            $nodeBuilder = new NodeBuilder('config', [
                'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:noNamespaceSchemaLocation' => 'urn:magento:framework:ObjectManager/etc/config.xsd'
            ]);

            $nodeBuilder
                ->elementNode('type', ['name' => $interceptedClassName])->children()
                    ->elementNode('plugin', ['name' => $pluginName, 'type' => $pluginClassName, 'sortOrder' => $sortOrder])
                ->endNode();
        }

        return new GeneratorResult(
            $nodeBuilder->toXml(),
            $this->getDiConfigFile($moduleNameEntity, $scope),
            'di'
        );
    }
}
