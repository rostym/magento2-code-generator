<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Layout;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\NodeBuilder;

/**
 * Class Index
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Layout
 */
class IndexGenerator extends AbstractGenerator
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName'];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];

        $nodeBuilder = new NodeBuilder('page',
            [
                'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:noNamespaceSchemaLocation' => 'urn:magento:framework:View/Layout/etc/page_configuration.xsd'
            ]
        );

        $nodeBuilder
            ->elementNode('body')->children()
                ->elementNode('referenceContainer', ['name'=> 'content'])->children()
                    ->elementNode('uiComponent', ['name' => $this->getUiComponentName($moduleNameEntity, $entityName)])
                ->children()
            ->children();

        return new GeneratorResult(
            $nodeBuilder->toXml(),
            sprintf(
                '%s/view/adminhtml/layout/%s_%s_index.xml',
                $moduleNameEntity->asPartOfPath(),
                mb_strtolower($moduleNameEntity->value()),
                mb_strtolower($entityName)),
            $entityName
        );
    }

    private function getUiComponentName(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        $moduleName = mb_strtolower($moduleNameEntity->value());
        $entityName = mb_strtolower($entityName);

        return sprintf('%s_%s_listing', $moduleName, $entityName);
    }
}
