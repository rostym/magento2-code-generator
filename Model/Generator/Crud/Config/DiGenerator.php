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
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
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
class DiGenerator extends AbstractGenerator
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName', 'resourceModelName', 'gridCollectionClass', 'tableDescriberResult'];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        $resourceModelName = $additionalArguments['resourceModelName'];
        $gridCollectionClass = $additionalArguments['gridCollectionClass'];
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];

        $nodeBuilder = new NodeBuilder('config', [
            'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:noNamespaceSchemaLocation' => 'urn:magento:framework:ObjectManager/etc/config.xsd'
        ]);

        $nodeBuilder
            ->elementNode('type', ['name' => CollectionFactory::class])->children()
                ->elementNode('arguments')->children()
                    ->elementNode('argument', ['name' => 'collections', 'xsi:type' => 'array'])->children()
                        ->elementNode('item', ['name' => NameUtil::generateDataSourceName($moduleNameEntity, $entityName), 'xsi:type' => 'string'], $gridCollectionClass)
                    ->endNode()
                ->endNode()
            ->endNode();

        $nodeBuilder
            ->elementNode('type', ['name' => $gridCollectionClass])->children()
                ->elementNode('arguments')->children()
                    ->elementNode('argument', ['name' => 'mainTable', 'xsi:type' => 'string'], $tableDescriberResult->tableName())
                    ->elementNode('argument', ['name' => 'resourceModel', 'xsi:type' => 'string'], $resourceModelName)
                ->endNode()
            ->endNode();

        return new GeneratorResult(
            $nodeBuilder->toXml(),
            sprintf('%s/etc/di.xml', $moduleNameEntity->asPartOfPath()),
            ''
        );
    }
}
