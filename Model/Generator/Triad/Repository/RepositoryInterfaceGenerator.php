<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad\Repository;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Builder\InterfaceBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Zend\Code\Generator\FileGenerator;

/**
 * Class RepositoryInterfacePart
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class RepositoryInterfaceGenerator extends AbstractGenerator
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName', 'entityInterfaceName'];
    }

    /**
     * @iheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        $entityInterfaceName = $additionalArguments['entityInterfaceName'];

        $currentClassName = sprintf('\%s\Api\%sRepositoryInterface', $moduleNameEntity->asPartOfNamespace(), $entityName);

        $interfaceBuilder = new InterfaceBuilder($currentClassName);
        $lcFirstEntityName = lcfirst($entityName);
        $interfaceBuilder
            ->addUse('Magento\Framework\Api\SearchCriteriaInterface')
            ->startDocBlockBuilding()
                ->disableWordWrap()
                ->shortDescription(sprintf('Interface %s', $entityName))
                ->addTag('package', sprintf('\%s\Api', $moduleNameEntity->asPartOfNamespace()))
            ->finishBuilding()
            ->startMethodBuilding('save')
                ->addArgument($lcFirstEntityName, $entityInterfaceName)
                ->startDocBlockBuilding()
                    ->shortDescription(sprintf('Save %s', $entityName))
                    ->addTag('param', sprintf('%s $%s', $entityInterfaceName, $lcFirstEntityName))
                    ->addEmptyLine()
                    ->addTag('return', $entityInterfaceName)
                    ->addTag('throws', '\Magento\Framework\Exception\CouldNotSaveException')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('getById')
                ->addArgument(sprintf('%sId', $lcFirstEntityName))
                ->startDocBlockBuilding()
                    ->shortDescription(sprintf('Get %s by id.', $entityName))
                    ->addTag('param', sprintf('int $%sId', $lcFirstEntityName))
                    ->addEmptyLine()
                    ->addTag('return', $entityInterfaceName)
                    ->addTag('throws', '\Magento\Framework\Exception\NoSuchEntityException')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('findById')
                ->addArgument(sprintf('%sId', $lcFirstEntityName))
                ->startDocBlockBuilding()
                    ->shortDescription(sprintf('Find %s by id.', $entityName))
                    ->addTag('param', sprintf('int $%sId', $lcFirstEntityName))
                    ->addEmptyLine()
                    ->addTag('return', sprintf('%s|null', $entityInterfaceName))
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('getList')
                ->addArgument('searchCriteria', 'SearchCriteriaInterface')
                ->startDocBlockBuilding()
                    ->shortDescription('Retrieve entity matching the specified criteria.')
                    ->addTag('param', 'SearchCriteriaInterface $searchCriteria')
                    ->addEmptyLine()
                    ->addTag('return', sprintf('%s[]', $entityInterfaceName))
                    ->addTag('throws', '\Magento\Framework\Exception\LocalizedException')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('delete')
                ->addArgument($lcFirstEntityName, $entityInterfaceName)
                ->startDocBlockBuilding()
                    ->shortDescription(sprintf('Delete %s', $entityName))
                    ->addTag('param', sprintf('%s $%s', $entityInterfaceName, $lcFirstEntityName))
                    ->addEmptyLine()
                    ->addTag('return', 'bool')
                    ->addTag('throws', '\Magento\Framework\Exception\CouldNotDeleteException')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('deleteById')
                ->addArgument(sprintf('%sId', $lcFirstEntityName))
                ->startDocBlockBuilding()
                    ->shortDescription('Delete entity by ID.')
                    ->addTag('param', sprintf('int $%sId', $lcFirstEntityName))
                    ->addEmptyLine()
                    ->addTag('return', 'bool')
                    ->addTag('throws', '\Magento\Framework\Exception\NoSuchEntityException')
                    ->addTag('throws', '\Magento\Framework\Exception\CouldNotDeleteException')
                ->finishBuilding()
            ->finishBuilding();

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($interfaceBuilder->build());

        return new GeneratorResult(
            $fileGenerator->generate(),
            sprintf('%s/Api/%sRepositoryInterface.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $currentClassName
        );
    }
}
