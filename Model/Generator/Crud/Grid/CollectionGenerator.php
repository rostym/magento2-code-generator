<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Grid;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Zend\Code\Generator\FileGenerator;

/**
 * Class Collection
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Grid
 */
class CollectionGenerator extends AbstractGenerator
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

        $className = sprintf(
            '\%s\Model\ResourceModel\%s\Grid\Collection',
            $moduleNameEntity->asPartOfNamespace(),
            $entityName
        );
        $classBuilder = new ClassBuilder($className);
        $classBuilder
            ->extendedFrom('\Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult');

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($classBuilder->build());

        return new GeneratorResult(
            $fileGenerator->generate(),
            sprintf('%s/Model/ResourceModel/%s/Grid/Collection.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $className
        );
    }
}
