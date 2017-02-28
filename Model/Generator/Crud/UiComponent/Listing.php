<?php

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
use Krifollk\CodeGenerator\Model\NodeBuilder;
use Krifollk\CodeGenerator\Model\TableInfo;
use Krifollk\CodeGenerator\Model\TableInfoFactory;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

/**
 * Class Listing
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent
 */
class Listing extends AbstractGenerator
{
    const FILE = BP . '/app/code/%s/view/adminhtml/ui_component/%s_%s_listing.xml';

    /** @var string */
    private $entityName;

    /** @var string */
    private $tableName;

    /** @var TableInfoFactory */
    private $tableInfoFactory;

    /** @var TableInfo\Column[] */
    private $columns;

    /**
     * @param string           $moduleName
     * @param string           $entityName
     * @param string           $tableName
     * @param TableInfoFactory $tableInfoFactory
     */
    public function __construct($moduleName, $entityName, $tableName, TableInfoFactory $tableInfoFactory)
    {
        parent::__construct($moduleName);
        $this->entityName       = $entityName;
        $this->tableName        = $tableName;
        $this->tableInfoFactory = $tableInfoFactory;
    }

    /**
     * Generate entity
     *
     * @return GeneratorResultInterface
     * @throws \InvalidArgumentException
     */
    public function generate()
    {
        $listing = new NodeBuilder('listing', [
            'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:noNamespaceSchemaLocation' => 'urn:magento:module:Magento_Ui:etc/ui_configuration.xsd'
        ]);

        $listing
            ->argumentNode('data', 'array')->children()
                ->itemNode('js_config', 'array')->children()
                    ->itemNode('provider', 'string', $this->generateProvider())
                    ->itemNode('deps', 'string', $this->generateDeps())
                ->endNode()
                ->itemNode('spinner', 'string', $this->generateSpinnerColumnsName())
                ->itemNode('buttons', 'array')->children()
                    ->itemNode('add', 'array')->children()
                        ->itemNode('name', 'string', 'add')
                        ->itemNode('label', 'string', 'Add', ['translate' => 'true'])
                        ->itemNode('primary', 'string', '')
                        ->itemNode('url', 'string', '*/*/new')
                    ->endNode()
                ->endNode()
            ->endNode()
            ->elementNode('dataSource', '', ['name' => $this->generateDataSourceName()])->children()
                ->argumentNode('dataProvider', 'configurableObject')->children()
                    ->argumentNode('class', 'string', DataProvider::class)
                    ->argumentNode('name', 'string', $this->generateDataSourceName())
                    ->argumentNode('primaryFieldName', 'string', $this->getPrimaryFieldName())
                    ->argumentNode('requestFieldName', 'string', 'id')//todo
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('component', 'string', 'Magento_Ui/js/grid/provider')
                            ->itemNode('update_url', 'string', '', ['path' => 'mui/index/render'])
                            ->itemNode('storageConfig', 'array')->children()
                                ->itemNode('indexField', 'string', $this->getPrimaryFieldName())
                            ->endNode()
                        ->endNode()
                    ->endNode()
                ->endNode()
            ->endNode()
            ->elementNode('listingToolbar', '', ['name' => 'listing_top'])->children()
                ->argumentNode('data', 'array')->children()
                    ->itemNode('config', 'array')->children()
                        ->itemNode('sticky', 'boolean', 'true')
                    ->endNode()
                ->endNode()
                ->elementNode('bookmark', '', ['name' => 'bookmarks'])
                ->elementNode('columnsControls', '', ['name' => 'columns_controls'])
                ->elementNode('filterSearch', '', ['name' => 'fulltext'])
                ->elementNode('filters', '', ['name' => 'listing_filters'])->children()
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
                ->elementNode('massaction', '', ['name' => 'listing_massaction'])->children()
                    ->elementNode('action','', ['name' => 'delete'])->children()
                        ->argumentNode('data', 'array')->children()
                            ->itemNode('config', 'array')->children()
                                ->itemNode('type', 'string', 'delete')
                                ->itemNode('label', 'string', 'Delete', ['translate' => 'true'])
                                ->itemNode('url', 'url', '', ['path' => '//massDelete'])//todo
                                ->itemNode('confirm', 'array')->children()
                                    ->itemNode('title', 'string', 'Delete items', ['translate' => 'true'])
                                    ->itemNode('message', 'string', 'Are you sure you wan\'t to delete selected items?', ['translate' => 'true'])
                                ->endNode()
                            ->endNode()
                        ->endNode()
                    ->endNode()
                    ->elementNode('action','', ['name' => 'edit'])->children()
                        ->argumentNode('data', 'array')->children()
                            ->itemNode('config', 'array')->children()
                                ->itemNode('type', 'string', 'edit')
                                ->itemNode('label', 'string', 'Edit', ['translate' => 'true'])
                                ->itemNode('callback', 'array')->children()
                                    ->itemNode('provider', 'string', $this->getFieldActionProvider())
                                    ->itemNode('target', 'string', 'editSelected')
                                ->endNode()
                            ->endNode()
                        ->endNode()
                    ->endNode()
                ->endNode()
                ->elementNode('paging', '', ['name' => 'listing_paging'])
            ->endNode()
            ->elementNode('columns', '', ['name' => $this->generateSpinnerColumnsName()])->children()
                ->argumentNode('data', 'array')->children()
                    ->itemNode('config', 'array')->children()
                        ->itemNode('editorConfig', 'array')->children()
                            ->itemNode('selectProvider', 'string', $this->getSelectProvider())
                            ->itemNode('enabled', 'boolean', 'true')
                            ->itemNode('indexField', 'string', $this->getPrimaryFieldName())
                            ->itemNode('clientConfig', 'array')->children()
                                ->itemNode('saveUrl', 'url', '', ['path' => $this->getInlineEditUrl()])
                                ->itemNode('validateBeforeSave', 'boolean', 'false')
                            ->endNode()
                        ->endNode()
                        ->itemNode('childDefaults', 'array')->children()
                            ->itemNode('fieldAction', 'array')->children()
                                ->itemNode('provider', 'string', $this->getFieldActionProvider())
                                ->itemNode('target', 'string', 'startEdit')
                                ->itemNode('params', 'array')->children()
                                    ->itemNode('0', 'string', '${ $.$data.rowIndex }')
                                    ->itemNode('1', 'boolean', 'true')
                                ->endNode()
                            ->endNode()
                        ->endNode()
                    ->endNode()
                ->endNode()
                ->elementNode('selectionsColumn', '', ['name' => 'ids'])->children()
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('indexField', 'string', $this->getPrimaryFieldName())
                        ->endNode()
                    ->endNode()
                ->endNode();

        foreach ($this->getColumns() as $column) {
            $listing
                ->elementNode('column', '', ['name' => $column->getName()])->children()
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('filter', 'string', 'text')
                            ->itemNode('label', 'string', str_replace('_', ' ', $column->getName()), ['translate' => 'true'])
                        ->endNode()
                    ->endNode()
                ->endNode();
        }

        return new GeneratorResult(
            $listing->toXml(),
            $this->getDestinationFile(),
            $this->entityName
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    private function generateProvider()
    {
        return sprintf('%s.%s',
            NameUtil::generateListingName(str_replace('/', '_', $this->moduleName), $this->entityName),
            NameUtil::generateDataSourceName(str_replace('/', '_', $this->moduleName), $this->entityName)
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    private function generateDeps()
    {
        return $this->generateProvider();
    }

    /**
     * @return string
     */
    private function generateSpinnerColumnsName()
    {
        return sprintf('%s_%s_columns', $this->getNormalizedModuleName(), $this->getNormalizedEntityName());
    }

    /**
     * @return string
     */
    private function getNormalizedModuleName()
    {
        return mb_strtolower(str_replace('/', '_', $this->moduleName));
    }

    /**
     * @return string
     */
    private function getNormalizedEntityName()
    {
        return lcfirst($this->entityName);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    private function generateDataSourceName(): string
    {
        return NameUtil::generateDataSourceName(str_replace('/', '_', $this->moduleName), $this->entityName);
    }

    /**
     * @return string
     */
    private function getPrimaryFieldName()
    {
        //TODO Refactor
        foreach ($this->getColumns() as $tableColumn) {
            if ($tableColumn->isIsPrimary()) {
                return $tableColumn->getName();
            }
        }

        return 'undefined';
    }

    /**
     * @return TableInfo\Column[]
     */
    private function getColumns()
    {
        if ($this->columns === null) {
            $this->columns = $this->tableInfoFactory->create(['tableName' => $this->tableName])->getColumns();
        }

        return $this->columns;
    }

    /**
     * @return string
     */
    private function getFieldActionProvider()
    {
        return sprintf(
            '%s.%s.%s_%s_columns_editor',
            $this->getListingName(),
            $this->getListingName(),
            $this->getNormalizedModuleName(),
            $this->getNormalizedEntityName()
        );
    }

    /**
     * @return string
     */
    private function getListingName()
    {
        return sprintf('%s_%s_listing', $this->getNormalizedModuleName(), $this->getNormalizedEntityName());
    }

    /**
     * @return string
     */
    private function getSelectProvider()
    {
        return sprintf(
            '%s.%s.%s_%s_columns.ids',
            $this->getListingName(),
            $this->getListingName(),
            $this->getNormalizedModuleName(),
            $this->getNormalizedEntityName()
        );
    }

    /**
     * @return string
     */
    private function getInlineEditUrl()
    {
        return sprintf('%s/%s/inlineEdit', mb_strtolower($this->moduleName), $this->getNormalizedEntityName());
    }

    /**
     * @return string
     */
    private function getDestinationFile()
    {
        $normalizedModuleName = mb_strtolower(str_replace('/', '_', $this->moduleName));
        $entityName = lcfirst($this->entityName);

        return sprintf(self::FILE, $this->moduleName, $normalizedModuleName, $entityName);
    }
}
