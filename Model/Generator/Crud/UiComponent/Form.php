<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\NodeBuilder;
use Krifollk\CodeGenerator\Model\TableInfoFactory;

class Form extends \Krifollk\CodeGenerator\Model\Generator\AbstractGenerator
{
    const FILE = BP . '/app/code/%s/view/adminhtml/ui_component/%s.xml';

    private $entityName;
    private $tableName;
    private $tableInfoFactory;
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
        $this->entityName = $entityName;
        $this->tableName = $tableName;
        $this->tableInfoFactory = $tableInfoFactory;
    }

    /**
     * Generate entity
     *
     * @return GeneratorResultInterface
     */
    public function generate()
    {
        $form = new NodeBuilder('form', [
            'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:noNamespaceSchemaLocation' => 'urn:magento:module:Magento_Ui:etc/ui_configuration.xsd'
        ]);

        $form
            ->argumentNode('data', 'array')->children()
                ->itemNode('js_config', 'array')->children()
                    ->itemNode('provider', 'string', $this->generateJsConfigProviderName())
                    ->itemNode('deps', 'string', $this->generateJsConfigDepsName())
                ->endNode()
                ->itemNode('label', 'string', 'General Information', ['translate' => 'true'])
                ->itemNode('config', 'array')->children()
                    ->itemNode('dataScope', 'string', 'data')
                    ->itemNode('namespace', 'string', $this->generateNamespaceName())
                ->endNode()
                ->itemNode('template', 'string', 'templates/form/collapsible')
                ->itemNode('buttons', 'array')->children()
                    ->itemNode('back', 'string', '')
                    ->itemNode('delete', 'string', '')
                    ->itemNode('reset', 'string', '')
                    ->itemNode('save', 'string', '')
                    ->itemNode('save_and_continue', 'string', '')
                ->endNode()
            ->endNode()
            ->elementNode('dataSource', ['name' => $this->generateProviderName()])->children()
                ->argumentNode('dataProvider', 'configurableObject')->children()
                    ->argumentNode('class', 'string', '')//TODO
                    ->argumentNode('name', 'string', $this->generateProviderName())
                    ->argumentNode('primaryFieldName', 'string', $this->getPrimaryFieldName())
                    ->argumentNode('requestFieldName', 'string', $this->getPrimaryFieldName())
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('submit_url', 'url', '', ['path' => $this->generateSubmitUrl()])
                        ->endNode()
                    ->endNode()
                ->endNode()
                ->argumentNode('data', 'array')->children()
                    ->itemNode('js_config', 'array')->children()
                        ->itemNode('component', 'string', 'Magento_Ui/js/form/provider')
                    ->endNode()
                ->endNode()
            ->endNode()
            ->elementNode('fieldset', ['name' => 'general'])->children()
                ->argumentNode('data', 'array')->children()
                    ->itemNode('config', 'array')->children()
                        ->itemNode('label', 'string')
                    ->endNode()
                ->endNode();

        foreach ($this->getColumns() as $column) {
            $form->elementNode('field', ['name' => $column->getName()])->children()
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('visible', 'boolean', 'true')
                            ->itemNode('dataType', 'string', 'text')
                            ->itemNode('label', 'string', ucfirst(str_replace('_', ' ', $column->getName())), ['translate' => 'true'])
                            ->itemNode('formElement', 'string', 'input')
                            ->itemNode('source', 'string', lcfirst($this->entityName))
                            ->itemNode('dataScope', 'string', $column->getName())
                        ->endNode()
                    ->endNode()
                ->endNode();
        }

        $form->endNode();

        return new GeneratorResult(
            $form->toXml(),
            $this->getDestinationFile(),
            ''
        );
    }

    protected function generateJsConfigProviderName()
    {
        return sprintf(
            '%s_%s_form.%s',
            strtolower(str_replace('/', '_', $this->moduleName)),
            strtolower($this->entityName),
            $this->generateProviderName()
        );
    }

    private function generateProviderName()
    {
        return sprintf('%s_form_data_source', strtolower($this->entityName));
    }

    /**
     * @return string
     */
    protected function generateJsConfigDepsName()
    {
        return $this->generateJsConfigProviderName();
    }

    private function generateNamespaceName()
    {
        return sprintf(
            '%s_%s_form',
            strtolower(str_replace('/', '_', $this->moduleName)),
            strtolower($this->entityName)
        );
    }

    protected function getPrimaryFieldName()
    {
        foreach ($this->getColumns() as $column) {
            if ($column->isIsPrimary()) {
                return $column->getName();
            }
        }

        return '';
    }

    /**
     * @return \Krifollk\CodeGenerator\Model\TableInfo\Column[]
     */
    protected function getColumns()
    {
        if ($this->columns === null) {
            $this->columns = $this->tableInfoFactory->create(['tableName' => $this->tableName])->getColumns();
        }

        return $this->columns;
    }

    private function generateSubmitUrl()
    {
        return sprintf('%s/%s/save', strtolower(str_replace('/', '_', $this->moduleName)), lcfirst($this->entityName));
    }

    /**
     * @return string
     */
    protected function getDestinationFile()
    {
        return sprintf(self::FILE, $this->moduleName, $this->generateNamespaceName());
    }
}
