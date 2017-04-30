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
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\NodeBuilder;
use Krifollk\CodeGenerator\Model\TableDescriber\Result;

/**
 * Class Form
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent
 */
class FormGenerator extends \Krifollk\CodeGenerator\Model\Generator\AbstractGenerator
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName', 'tableDescriberResult'];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];
        $exposedMessages = [];

        $destinationFile = sprintf(
            '%s/view/adminhtml/ui_component/%s.xml',
            $moduleNameEntity->asPartOfPath(),
            $this->generateNamespaceName($moduleNameEntity, $entityName)
        );
        try {
            $primaryFieldName = $tableDescriberResult->primaryColumn()->name();
        } catch (\RuntimeException $e) {
            $exposedMessages[] = '';//todo
            $primaryFieldName = '<--COLUMN_NAME->';
        }

        $form = new NodeBuilder('form', [
            'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:noNamespaceSchemaLocation' => 'urn:magento:module:Magento_Ui:etc/ui_configuration.xsd'
        ]);

        $form
            ->argumentNode('data', 'array')->children()
                ->itemNode('js_config', 'array')->children()
                    ->itemNode('provider', 'string', $this->generateJsConfigProviderName($moduleNameEntity, $entityName))
                    ->itemNode('deps', 'string', $this->generateJsConfigDepsName($moduleNameEntity, $entityName))
                ->endNode()
                ->itemNode('label', 'string', 'General Information', ['translate' => 'true'])
                ->itemNode('config', 'array')->children()
                    ->itemNode('dataScope', 'string', 'data')
                    ->itemNode('namespace', 'string', $this->generateNamespaceName($moduleNameEntity, $entityName))
                ->endNode()
                ->itemNode('template', 'string', 'templates/form/collapsible')
                ->itemNode('buttons', 'array')->children()
                    ->itemNode('back', 'array')->children()
                        ->itemNode('name', 'string', 'back')
                        ->itemNode('label', 'string', 'Back', ['translate' => 'true'])
                        ->itemNode('class', 'string', 'back')
                        ->itemNode('sort_order', 'number', 50)
                        ->itemNode('url', 'string', '*/*/')
                    ->endNode()
                    ->itemNode('save', 'array', '')->children()
                        ->itemNode('name', 'string', 'save')
                        ->itemNode('label', 'string', 'Save', ['translate' => 'true'])
                        ->itemNode('class', 'string', 'save primary')
                        ->itemNode('sort_order', 'number', 100)
                        ->itemNode('data_attribute', 'array')->children()
                            ->itemNode('mage-init', 'array')->children()
                                ->itemNode('button', 'array')->children()
                                    ->itemNode('event', 'string', 'save')
                                ->endNode()
                            ->endNode()
                            ->itemNode('form-role', 'string', 'save')
                        ->endNode()
                    ->endNode()
                    ->itemNode('save_and_continue', 'array', '')->children()
                        ->itemNode('name', 'string', 'save')
                        ->itemNode('label', 'string', 'Save and Continue Edit', ['translate' => 'true'])
                        ->itemNode('class', 'string', 'save')
                        ->itemNode('sort_order', 'number', 150)
                        ->itemNode('data_attribute', 'array')->children()
                            ->itemNode('mage-init', 'array')->children()
                                ->itemNode('button', 'array')->children()
                                    ->itemNode('event', 'string', 'saveAndContinueEdit')
                                ->endNode()
                            ->endNode()
                        ->endNode()
                    ->endNode()
                    ->itemNode('reset', 'array')->children()
//                        ->itemNode('name', 'string', 'reset')
                        ->itemNode('label', 'string', 'Reset', ['translate' => 'true'])
                        ->itemNode('class', 'string', 'reset')
                        ->itemNode('on_click', 'string', 'location.reload();')
                        ->itemNode('sort_order', 'number', 200)
                    ->endNode()
                ->endNode()
            ->endNode()
            ->elementNode('dataSource', ['name' => $this->generateProviderName($entityName)])->children()
                ->argumentNode('dataProvider', 'configurableObject')->children()
                    ->argumentNode('class', 'string', $additionalArguments['dataProvider'])
                    ->argumentNode('name', 'string', $this->generateProviderName($entityName))
                    ->argumentNode('primaryFieldName', 'string', $primaryFieldName)
                    ->argumentNode('requestFieldName', 'string', $primaryFieldName)
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('submit_url', 'url', '', ['path' => $this->generateSubmitUrl($moduleNameEntity, $entityName)])
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

        foreach ($tableDescriberResult->columns() as $column) {
            $form->elementNode('field', ['name' => $column->name()])->children()
                    ->argumentNode('data', 'array')->children()
                        ->itemNode('config', 'array')->children()
                            ->itemNode('visible', 'boolean', 'true')
                            ->itemNode('dataType', 'string', 'text')
                            ->itemNode('label', 'string', NameUtil::generateLabelFromColumn($column), ['translate' => 'true'])
                            ->itemNode('formElement', 'string', 'input')
                            ->itemNode('source', 'string', lcfirst($entityName))
                            ->itemNode('dataScope', 'string', $column->name())
                        ->endNode()
                    ->endNode()
                ->endNode();
        }

        $form->endNode();

        return new GeneratorResult(
            $form->toXml(),
            $destinationFile,
            'ui_component_form',
            $exposedMessages
        );
    }

    private function generateJsConfigProviderName(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return sprintf(
            '%s_%s_form.%s',
            strtolower($moduleNameEntity->value()),
            strtolower($entityName),
            $this->generateProviderName($entityName)
        );
    }

    private function generateProviderName(string $entityName): string
    {
        return sprintf('%s_form_data_source', strtolower($entityName));
    }

    private function generateJsConfigDepsName(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return $this->generateJsConfigProviderName($moduleNameEntity, $entityName);
    }

    private function generateNamespaceName(ModuleNameEntity $moduleNameEntity, string $entityName)
    {
        return sprintf(
            '%s_%s_form',
            strtolower($moduleNameEntity->value()),
            strtolower($entityName)
        );
    }

    private function generateSubmitUrl(ModuleNameEntity $moduleNameEntity, string $entityName)
    {
        return sprintf('%s/%s/save', NameUtil::generateModuleFrontName($moduleNameEntity), lcfirst($entityName));
    }
}
