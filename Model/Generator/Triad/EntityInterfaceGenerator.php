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
use Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\TableDescriber\Result;
use Zend\Code\Generator\FileGenerator;

/**
 * Class ModelInterface
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
class EntityInterfaceGenerator extends AbstractGenerator
{
    const MODEL_INTERFACE_NAME_PATTERN      = '\%s\Api\Data\%sInterface';
    const MODEL_INTERFACE_FILE_NAME_PATTERN = '%s/Api/Data/%sInterface.php';
    const PACKAGE_NAME_PATTERN              = '%s\Api\Data';

    /**#@+
     * Doc block patterns
     */
    const GETTER_DOC_PATTERN = 'Get %s value.';
    const SETTER_DOC_PATTERN = 'Set %s value.';
    /**#@-*/

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName', 'tableDescriberResult'];
    }

    /**
     * @inheritdoc
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface
    {
        $entityName = $additionalArguments['entityName'];
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];

        $fullEntityName = sprintf(
            self::MODEL_INTERFACE_NAME_PATTERN,
            $moduleNameEntity->asPartOfNamespace(),
            $entityName
        );

        $interfaceBuilder = new InterfaceBuilder($fullEntityName);
        $interfaceBuilder
            ->startDocBlockBuilding()
                ->disableWordWrap()
                ->shortDescription(sprintf('Interface %s', $entityName))
                ->addTag('package', sprintf(self::PACKAGE_NAME_PATTERN, $moduleNameEntity->asPartOfNamespace()))
            ->finishBuilding();

        foreach ($tableDescriberResult->columns() as $column) {
            $camelizedColumnName = NameUtil::camelize($column->name());
            $argumentName = lcfirst($camelizedColumnName);
            $interfaceBuilder
                ->addConstant(strtoupper($column->name()), $column->name())
                ->startMethodBuilding(sprintf('get%s', $camelizedColumnName))
                    ->startDocBlockBuilding()
                        ->disableWordWrap()
                        ->shortDescription(sprintf(self::GETTER_DOC_PATTERN, $argumentName))
                        ->addTag('return', $column->type())
                    ->finishBuilding()
                ->finishBuilding()
                ->startMethodBuilding(sprintf('set%s', $camelizedColumnName))
                    ->addArgument($argumentName)
                    ->startDocBlockBuilding()
                        ->shortDescription(sprintf(self::SETTER_DOC_PATTERN, $argumentName))
                        ->addTag('param', sprintf('%s $%s', $column->type(), $argumentName))
                        ->addEmptyLine()
                        ->addTag('return', '$this')
                    ->finishBuilding()
                ->finishBuilding();
        }

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($interfaceBuilder->build());

        return new GeneratorResult(
            $fileGenerator->generate(),
            sprintf(self::MODEL_INTERFACE_FILE_NAME_PATTERN, $moduleNameEntity->asPartOfPath(), $entityName),
            $fullEntityName
        );
    }
}
