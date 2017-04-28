<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Zend\Code\Generator\FileGenerator;

/**
 * Class CollectionPart
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class CollectionGenerator extends AbstractGenerator
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName', 'resourceClass', 'modelClass'];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        $resourceClass = $additionalArguments['resourceClass'];
        $modelClass = $additionalArguments['modelClass'];
        $className = NameUtil::generateCollectionName($moduleNameEntity, $entityName);

        $classBuilder = new ClassBuilder($className);

        $classBuilder
            ->extendedFrom('\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection')
            ->startDocBlockBuilding()
                ->disableWordWrap()
                ->shortDescription(sprintf('Class %s', $entityName))
                ->addTag('method', sprintf('%s getResource()', $resourceClass))
                ->addTag('method', sprintf('%s[] getItems()', $modelClass))
                ->addTag('method', sprintf('%s[] getItemsByColumnValue($column, $value)', $modelClass))
                ->addTag('method', sprintf('%s getFirstItem()', $modelClass))
                ->addTag('method', sprintf('%s getLastItem()', $modelClass))
                ->addTag('method', sprintf('%s getItemByColumnValue($column, $value)', $modelClass))
                ->addTag('method', sprintf('%s getItemById($idValue)', $modelClass))
                ->addTag('method', sprintf('%s getNewEmptyItem()', $modelClass))
                ->addTag('method', sprintf('%s fetchItem()', $modelClass))
                ->addTag('property', sprintf('%s[] _items', $modelClass))
                ->addTag('property', sprintf('%s _resource', $resourceClass))
            ->finishBuilding()
            ->startPropertyBuilding('_eventPrefix')
                ->markAsProtected()
                ->defaultValue(mb_strtolower(sprintf('%s_%s_collection', $moduleNameEntity->value(), $entityName)))
            ->finishBuilding()
            ->startPropertyBuilding('_eventObject')
                ->markAsProtected()
                ->defaultValue('object')
            ->finishBuilding()
            ->startMethodBuilding('_construct')
                ->markAsProtected()
                ->withBody(sprintf('$this->_init(%s::class, %s::class);', $modelClass, $resourceClass))
                ->startDocBlockBuilding()
                    ->addTag('inheritdoc', '')
                ->finishBuilding()
            ->finishBuilding();

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($classBuilder->build());

        return new GeneratorResult(
            $fileGenerator->generate(),
            sprintf('%s/Model/ResourceModel/%s/Collection.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $className
        );
    }
}
