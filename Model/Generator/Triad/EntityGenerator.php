<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
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
use Krifollk\CodeGenerator\Model\TableDescriber\Result;
use Krifollk\CodeGenerator\Model\TableDescriber\Result\Column;
use Zend\Code\Generator\FileGenerator;


/**
 * Class Model
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
class EntityGenerator extends AbstractGenerator
{
    const MODEL_NAME_PATTERN      = '\%s\Model\%s';
    const PACKAGE_NAME_PATTERN    = '%s\Model';
    const MODEL_FILE_NAME_PATTERN = '%s/Model/%s.php';

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return [
            'entityName',
            'entityInterface',
            'tableDescriberResult',
            'resourceEntityName',
            'entityCollectionName',
        ];
    }

    /**
     * @inheritdoc
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];
        $resourceEntityName = $additionalArguments['resourceEntityName'];
        $entityCollectionName = $additionalArguments['entityCollectionName'];
        $entityInterface = $additionalArguments['entityInterface'];

        $fullEntityName = sprintf(
            self::MODEL_NAME_PATTERN,
            $moduleNameEntity->asPartOfNamespace(),
            $entityName
        );

        $interfaceBuilder = new ClassBuilder($fullEntityName);
        $interfaceBuilder
            ->startDocBlockBuilding()
                ->disableWordWrap()
                ->shortDescription(sprintf('Class %s', $entityName))
                ->addTag('method', sprintf('%s getResource()', $resourceEntityName))
                ->addTag('method', sprintf('%s getCollection()', $entityCollectionName))
                ->addTag('method', sprintf('%s getResourceCollection()', $entityCollectionName))
                ->addEmptyLine()
                ->addTag('package', sprintf(self::PACKAGE_NAME_PATTERN, $moduleNameEntity->asPartOfNamespace()))
            ->finishBuilding()
            ->extendedFrom('\Magento\Framework\Model\AbstractModel')
            ->implementsInterface($entityInterface)
            ->startPropertyBuilding('_eventPrefix')
                ->markAsProtected()
                ->defaultValue(mb_strtolower(sprintf('%s_model_%s', $moduleNameEntity->value(), $entityName)))
            ->finishBuilding()
            ->startMethodBuilding('_construct')
                ->markAsProtected()
                ->withBody(sprintf('$this->_init(%s::class);', $resourceEntityName))
                ->startDocBlockBuilding()
                    ->addTag('inheritdoc', '')
                ->finishBuilding()
            ->finishBuilding();

        foreach ($tableDescriberResult->columns() as $column) {
            $camelizedColumnName = NameUtil::camelize($column->name());
            $argumentName = lcfirst($camelizedColumnName);
            $interfaceBuilder
                ->startMethodBuilding(sprintf('get%s', $camelizedColumnName))
                    ->withBody(sprintf('return $this->_getData(self::%s);', strtoupper($column->name())))
                    ->startDocBlockBuilding()
                        ->addTag('inheritdoc', '')
                    ->finishBuilding()
                ->finishBuilding()
                ->startMethodBuilding(sprintf('set%s', $camelizedColumnName))
                    ->addArgument($argumentName)
                    ->withBody($this->generateSetterBody($column, $argumentName))
                    ->startDocBlockBuilding()
                        ->addTag('inheritdoc', '')
                    ->finishBuilding()
                ->finishBuilding();
        }

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($interfaceBuilder->build());

        return new GeneratorResult(
            $fileGenerator->generate(),
            sprintf(self::MODEL_FILE_NAME_PATTERN, $moduleNameEntity->asPartOfPath(), $entityName),
            $fullEntityName
        );
    }

    private function generateSetterBody(Column $column, string $argumentName): string
    {
        return sprintf('$this->setData(self::%s, $%s);
        
return $this;', strtoupper($column->name()), $argumentName);
    }
}
