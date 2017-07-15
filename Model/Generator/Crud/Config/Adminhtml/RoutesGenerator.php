<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Config\Adminhtml;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\NodeBuilder;

/**
 * Class RoutesGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Config\Adminhtml
 */
class RoutesGenerator extends AbstractGenerator
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $moduleFrontName = NameUtil::generateModuleFrontName($moduleNameEntity);

        $nodeBuilder = new NodeBuilder('config', [
            'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:noNamespaceSchemaLocation' => 'urn:magento:framework:App/etc/routes.xsd'
        ]);

        $nodeBuilder
            ->elementNode('router', ['id' => 'admin'])->children()
                ->elementNode('route', ['id' => $moduleFrontName, 'frontName' => $moduleFrontName])->children()
                    ->elementNode('module', ['name' => $moduleNameEntity->value(), 'before' => 'Magento_Backend'])
                ->endNode()
            ->endNode();

        return new GeneratorResult(
            $nodeBuilder->toXml(),
            sprintf('%s/etc/adminhtml/routes.xml', $moduleNameEntity->asPartOfPath()),
            $moduleFrontName
        );
    }
}
