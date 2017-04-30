<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\NodeBuilder;
use Krifollk\CodeGenerator\Model\TableDescriber\Result;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

/**
 * Class ListingGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent
 */
class ListingGenerator extends AbstractGenerator
{
    const FILE               = '%s/view/adminhtml/ui_component/%s_%s_listing.xml';
    const REQUEST_FIELD_NAME = 'id';

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName', 'tableDescriberResult', 'actionsColumnClass'];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $listing = new NodeBuilder('listing', [
            'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:noNamespaceSchemaLocation' => 'urn:magento:module:Magento_Ui:etc/ui_configuration.xsd'
        ]);
        $entityName = $additionalArguments['entityName'];
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];
        $exposedMessages = [];

        try {
            $primaryFieldName = $tableDescriberResult->primaryColumn()->name();
        } catch (\RuntimeException $e) {
            $exposedMessages[] = '';//todo
            $primaryFieldName = '<--COLUMN_NAME->';
        }

        $listing
            ->argumentNode('data', 'array')->children()
                ->itemNode('js_config', 'array')->children()
                    ->itemNode('provider', 'string', $this->generateProvider($moduleNameEntity, $entityName))
                    ->itemNode('deps', 'string', $this->generateDeps($moduleNameEntity, $entityName))
                ->endNode()
                ->itemNode('spinner', 'string', $this->generateSpinnerColumnsName($moduleNameEntity, $entityName))
                ->itemNode('buttons', 'array')->children()
                    ->itemNode('add', 'array')->children()
                        ->itemNode('name', 'string', 'add')
                        ->itemNode('label', 'string', 'Add', ['translate' => 'true'])
                        ->itemNode('primary', 'string', '')
                        ->itemNode('url', 'string', '*/*/new')
                    ->endNode()
                ->endNode()
            ->endNode()
            ->elementNode('dataSource', ['name' => $this->generateDataSourceName($moduleNameEntity, $entityName)])->children()
                ->argumentNode('dataProvider', 'configurableObject')->children()
                    ->argumentNode('class', 'string', DataProvider::class)
                    ->argumentNode('name', 'string', $this->generateDataSourceName($moduleNameEntity, $entityName))
                    ->argumentNode('primaryFieldName', 'string', $primaryFieldName)
                    ->argumentNode('requestFieldName', 'string', self::REQUEST_FIELD_NAME)
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('component', 'string', 'Magento_Ui/js/grid/provider')
                            ->itemNode('update_url', 'url', '', ['path' => 'mui/index/render'])
                            ->itemNode('storageConfig', 'array')->children()
                                ->itemNode('indexField', 'string', $primaryFieldName)
                            ->endNode()
                        ->endNode()
                    ->endNode()
                ->endNode()
            ->endNode()
            ->elementNode('listingToolbar', ['name' => 'listing_top'])->children()
                ->argumentNode('data', 'array')->children()
                    ->itemNode('config', 'array')->children()
                        ->itemNode('sticky', 'boolean', 'true')
                    ->endNode()
                ->endNode()
                ->elementNode('bookmark', ['name' => 'bookmarks'])
                ->elementNode('columnsControls', ['name' => 'columns_controls'])
                ->elementNode('filterSearch', ['name' => 'fulltext'])
                ->elementNode('filters', ['name' => 'listing_filters'])->children()
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('templates', 'array')->children()
                                ->itemNode('filters', 'array')->children()
                                    ->itemNode('select', 'array')->children()
                                        ->itemNode('component', 'string', 'Magento_Ui/js/form/element/ui-select')
                                        ->itemNode('template', 'string', 'ui/grid/filters/elements/ui-select')
                                    ->endNode()
                                ->endNode()
                            ->endNode()
                        ->endNode()
                    ->endNode()
                ->endNode()
                ->elementNode('massaction', ['name' => 'listing_massaction'])->children()
                    ->elementNode('action', ['name' => 'delete'], '')->children()
                        ->argumentNode('data', 'array')->children()
                            ->itemNode('config', 'array')->children()
                                ->itemNode('type', 'string', 'delete')
                                ->itemNode('label', 'string', 'Delete', ['translate' => 'true'])
                                ->itemNode('url', 'url', '', ['path' => $this->generateUrl($moduleNameEntity, $entityName, 'massDelete')])
                                ->itemNode('confirm', 'array')->children()
                                    ->itemNode('title', 'string', 'Delete items', ['translate' => 'true'])
                                    ->itemNode('message', 'string', 'Are you sure you wan\'t to delete selected items?', ['translate' => 'true'])
                                ->endNode()
                            ->endNode()
                        ->endNode()
                    ->endNode()
                    ->elementNode('action', ['name' => 'edit'])->children()
                        ->argumentNode('data', 'array')->children()
                            ->itemNode('config', 'array')->children()
                                ->itemNode('type', 'string', 'edit')
                                ->itemNode('label', 'string', 'Edit', ['translate' => 'true'])
                                ->itemNode('callback', 'array')->children()
                                    ->itemNode('provider', 'string', $this->getFieldActionProvider($moduleNameEntity, $entityName))
                                    ->itemNode('target', 'string', 'editSelected')
                                ->endNode()
                            ->endNode()
                        ->endNode()
                    ->endNode()
                ->endNode()
                ->elementNode('paging', ['name' => 'listing_paging'])
            ->endNode()
            ->elementNode('columns', ['name' => $this->generateSpinnerColumnsName($moduleNameEntity, $entityName)])->children()
                ->argumentNode('data', 'array')->children()
                    ->itemNode('config', 'array')->children()
                        ->itemNode('editorConfig', 'array')->children()
                            ->itemNode('selectProvider', 'string', $this->getSelectProvider($moduleNameEntity, $entityName))
                            ->itemNode('enabled', 'boolean', 'true')
                            ->itemNode('indexField', 'string', $primaryFieldName)
                            ->itemNode('clientConfig', 'array')->children()
                                ->itemNode('saveUrl', 'url', '', ['path' => $this->generateUrl($moduleNameEntity, $entityName, 'inlineEdit')])
                                ->itemNode('validateBeforeSave', 'boolean', 'false')
                            ->endNode()
                        ->endNode()
                        ->itemNode('childDefaults', 'array')->children()
                            ->itemNode('fieldAction', 'array')->children()
                                ->itemNode('provider', 'string', $this->getFieldActionProvider($moduleNameEntity, $entityName))
                                ->itemNode('target', 'string', 'startEdit')
                                ->itemNode('params', 'array')->children()
                                    ->itemNode('0', 'string', '${ $.$data.rowIndex }')
                                    ->itemNode('1', 'boolean', 'true')
                                ->endNode()
                            ->endNode()
                        ->endNode()
                    ->endNode()
                ->endNode()
                ->elementNode('selectionsColumn', ['name' => 'ids'])->children()
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('indexField', 'string', $primaryFieldName)
                        ->endNode()
                    ->endNode()
                ->endNode();

        foreach ($tableDescriberResult->columns() as $column) {
            $listing
                ->elementNode('column', ['name' => $column->name()])->children()
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('filter', 'string', 'text')
                            ->itemNode('label', 'string', NameUtil::generateLabelFromColumn($column), ['translate' => 'true'])
                        ->endNode()
                    ->endNode()
                ->endNode();
        }

        $listing
            ->elementNode('actionsColumn', ['name' => 'actions', 'class' => $additionalArguments['actionsColumnClass']])->children()
                ->argumentNode('data', 'array')->children()
                    ->itemNode('config', 'array')->children()
                        ->itemNode('indexField', 'string', $primaryFieldName)
                    ->endNode()
                ->endNode()
            ->endNode();

        return new GeneratorResult(
            $listing->toXml(),
            sprintf(self::FILE, $moduleNameEntity->asPartOfPath(), mb_strtolower($moduleNameEntity->value()), mb_strtolower($entityName)),
            $entityName,
            $exposedMessages
        );
    }

    private function generateProvider(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return sprintf('%s.%s',
            NameUtil::generateListingName($moduleNameEntity, $entityName),
            NameUtil::generateDataSourceName($moduleNameEntity, $entityName)
        );
    }

    private function generateDeps(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return $this->generateProvider($moduleNameEntity, $entityName);
    }

    private function generateSpinnerColumnsName(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return sprintf('%s_%s_columns', $moduleNameEntity->value(), lcfirst($entityName));
    }

    private function generateDataSourceName(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return NameUtil::generateDataSourceName($moduleNameEntity, $entityName);
    }

    private function getFieldActionProvider(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return sprintf(
            '%s.%s.%s_%s_columns_editor',
            $this->getListingName($moduleNameEntity, $entityName),
            $this->getListingName($moduleNameEntity, $entityName),
            mb_strtolower($moduleNameEntity->value()),
            lcfirst($entityName)
        );
    }

    private function getListingName(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return sprintf('%s_%s_listing', mb_strtolower($moduleNameEntity->value()), lcfirst($entityName));
    }

    private function getSelectProvider(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return sprintf(
            '%s.%s.%s_%s_columns.ids',
            $this->getListingName($moduleNameEntity, $entityName),
            $this->getListingName($moduleNameEntity, $entityName),
            mb_strtolower($moduleNameEntity->value()),
            lcfirst($entityName)
        );
    }

    private function generateUrl(ModuleNameEntity $moduleNameEntity, string $entityName, string $action): string
    {
        return sprintf('%s/%s/%s', NameUtil::generateModuleFrontName($moduleNameEntity), lcfirst($entityName), $action);
    }
}
