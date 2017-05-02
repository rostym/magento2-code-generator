<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Config;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractXmlGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\Generator\Triad\DiGenerator as TriadDiGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\NodeBuilder;
use Krifollk\CodeGenerator\Model\TableDescriber\Result;
use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory;

/**
 * Class DiGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Config
 */
class DiGenerator extends AbstractXmlGenerator
{
    const UI_COMPONENT_GRID_COLLECTIONS_XPATH = "//config/type[contains(@name,'Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory')]/arguments/argument[@name ='collections']";
    const UI_COMPONENT_GRID_COLLECTION_ITEM_XPATH = self ::UI_COMPONENT_GRID_COLLECTIONS_XPATH . "/item[contains(@name, '%s')]";
    const UI_COMPONENT_COLLECTION_XPATH = "//config/type[contains(@name,'%s')]";

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return [
            'entityName',
            'resourceModelName',
            'gridCollectionClass',
            'tableDescriberResult',
            'entityClass',
            'entityInterface',
            'repository',
            'repositoryInterface'
        ];
    }

    /**
     * @inheritdoc
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        $resourceModelName = $additionalArguments['resourceModelName'];
        $gridCollectionClass = ltrim($additionalArguments['gridCollectionClass'], '\\');
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];

        $entityClass = ltrim($additionalArguments['entityClass'], '\\');
        $entityInterface = ltrim($additionalArguments['entityInterface'], '\\');
        $repository = ltrim($additionalArguments['repository'], '\\');
        $repositoryInterface = ltrim($additionalArguments['repositoryInterface'], '\\');

        $dataSourceName = NameUtil::generateDataSourceName($moduleNameEntity, $entityName);
        $diFile = $this->getDiConfigFile($moduleNameEntity);
        if ($this->file->isExists($diFile)) {
            $domDocument = $this->load($diFile);
            $nodeBuilder = new NodeBuilder('', [], $domDocument);

            if ($nodeBuilder->isExistByPath(self::UI_COMPONENT_GRID_COLLECTIONS_XPATH)) {
                if (!$nodeBuilder->isExistByPath(sprintf(self::UI_COMPONENT_GRID_COLLECTION_ITEM_XPATH, NameUtil::generateDataSourceName($moduleNameEntity, $entityName)))) {
                    $nodeBuilder->trySetPointerToElement(self::UI_COMPONENT_GRID_COLLECTIONS_XPATH);
                    $nodeBuilder
                        ->elementNode('item', ['name' => $dataSourceName, 'xsi:type' => 'string'], $gridCollectionClass)
                        ->endNode();
                }
            } else {
                $this->addDataSourceCollectionsDefinition($nodeBuilder, $dataSourceName, $gridCollectionClass);
            }

            if (!$nodeBuilder->isExistByPath(sprintf(self::UI_COMPONENT_COLLECTION_XPATH, $gridCollectionClass))) {
                $this->addCollectionDifinition($nodeBuilder, $gridCollectionClass, $tableDescriberResult, $resourceModelName);
            }

            if (!$nodeBuilder->isExistByPath(sprintf(TriadDiGenerator::PREFERENCE_XPATH, $entityInterface))) {
                $nodeBuilder->elementNode('preference', ['for' => $entityInterface, 'type' => $entityClass]);
            }

            if (!$nodeBuilder->isExistByPath(sprintf(TriadDiGenerator::PREFERENCE_XPATH, $repositoryInterface))) {
                $nodeBuilder->elementNode('preference', ['for' => $repositoryInterface, 'type' => $repository]);
            }

        } else {
            $nodeBuilder = new NodeBuilder('config', [
                'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:noNamespaceSchemaLocation' => 'urn:magento:framework:ObjectManager/etc/config.xsd'
            ]);

            $nodeBuilder
                ->elementNode('preference', ['for' => $entityInterface, 'type' => $entityClass])
                ->elementNode('preference', ['for' => $repositoryInterface, 'type' => $repository]);
            $this->addDataSourceCollectionsDefinition($nodeBuilder, $dataSourceName, $gridCollectionClass);
            $this->addCollectionDifinition($nodeBuilder, $gridCollectionClass, $tableDescriberResult, $resourceModelName);
        }

        return new GeneratorResult(
            $nodeBuilder->toXml(),
            sprintf('%s/etc/di.xml', $moduleNameEntity->asPartOfPath()),
            ''
        );
    }

    private function addDataSourceCollectionsDefinition(
        NodeBuilder $nodeBuilder,
        string $dataSourceName,
        string $gridCollectionClass
    ) {
        $nodeBuilder
            ->elementNode('type', ['name' => CollectionFactory::class])->children()
                ->elementNode('arguments')->children()
                    ->elementNode('argument', ['name' => 'collections', 'xsi:type' => 'array'])->children()
                        ->elementNode('item', ['name' => $dataSourceName, 'xsi:type' => 'string'], $gridCollectionClass)
                    ->endNode()
                ->endNode()
            ->endNode();
    }

    private function addCollectionDifinition(
        NodeBuilder $nodeBuilder,
        string $gridCollectionClass,
        Result $tableDescriberResult,
        string $resourceModelName
    ) {
        $nodeBuilder
            ->elementNode('type', ['name' => $gridCollectionClass])->children()
                ->elementNode('arguments')->children()
                    ->elementNode('argument', ['name' => 'mainTable', 'xsi:type' => 'string'], $tableDescriberResult->tableName())
                    ->elementNode('argument', ['name' => 'resourceModel', 'xsi:type' => 'string'], $resourceModelName)
                ->endNode()
            ->endNode();
    }
}
