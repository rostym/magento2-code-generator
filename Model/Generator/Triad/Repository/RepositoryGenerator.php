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
use Krifollk\CodeGenerator\Model\ClassBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Zend\Code\Generator\FileGenerator;

/**
 * Class RepositoryGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad\Repository
 */
class RepositoryGenerator extends AbstractGenerator
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return [
            'entityName',
            'entityInterfaceName',
            'resourceEntityName',
            'entityCollectionName',
            'resourceEntityName',
            'repositoryInterfaceName'
        ];
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
        $resourceEntityName = $additionalArguments['resourceEntityName'];
        $entityCollectionName = $additionalArguments['entityCollectionName'];
        $repositoryInterfaceName = $additionalArguments['repositoryInterfaceName'];

        $currentClassName = sprintf('\%s\Model\%sRepository', $moduleNameEntity->asPartOfNamespace(), $entityName);

        $classBuilder = new ClassBuilder($currentClassName);
        $lcFirstEntityName = lcfirst($entityName);
        $entityFactoryClassArgumentName = sprintf('%sFactory', $lcFirstEntityName);
        $idArgumentName = sprintf('%sId', $lcFirstEntityName);

        $classBuilder
            ->implementsInterface($repositoryInterfaceName)
            ->addUse('Magento\Framework\Api\SearchCriteriaInterface')
            ->addUse('Magento\Framework\Api\SortOrder')
            ->addUse('Magento\Framework\Exception\NoSuchEntityException')
            ->addUse('Magento\Framework\Exception\CouldNotDeleteException')
            ->startPropertyBuilding('collectionFactory')
                ->markAsPrivate()
            ->finishBuilding()
            ->startPropertyBuilding($entityFactoryClassArgumentName)
                ->markAsPrivate()
            ->finishBuilding()
            ->startPropertyBuilding('resource')
                ->markAsPrivate()
            ->finishBuilding()
            ->startDocBlockBuilding()
                ->disableWordWrap()
                ->shortDescription(sprintf('Class %s', $entityName))
                ->addTag('package', sprintf('\%s\Model', $moduleNameEntity->asPartOfNamespace()))
            ->finishBuilding()
            ->startMethodBuilding('__construct')
                ->markAsPublic()
                ->addArgument('resource', $resourceEntityName)
                ->addArgument($entityFactoryClassArgumentName, sprintf('%sFactory', $entityInterfaceName))
                ->addArgument('collectionFactory', sprintf('%sFactory', $entityCollectionName))
                ->withBody($this->getConstructorBody($entityFactoryClassArgumentName))
            ->finishBuilding()
            ->startMethodBuilding('save')
                ->addArgument($lcFirstEntityName, $entityInterfaceName)
                ->withBody($this->getSaveMethodBody($lcFirstEntityName))
                ->startDocBlockBuilding()
                    ->addTag('inheritdoc', '')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('getById')
                ->addArgument($idArgumentName)
                ->withBody($this->getGetByIdBody($idArgumentName, $entityFactoryClassArgumentName, $lcFirstEntityName))
                ->startDocBlockBuilding()
                    ->addTag('inheritdoc', '')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('findById')
                ->withBody($this->getFindByIdBody($idArgumentName, $entityFactoryClassArgumentName, $lcFirstEntityName))
                ->addArgument($idArgumentName)
                ->startDocBlockBuilding()
                    ->addTag('inheritdoc', '')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('getList')
                ->addArgument('searchCriteria', 'SearchCriteriaInterface')
                ->withBody($this->getGetListMethodBody())
                ->startDocBlockBuilding()
                    ->addTag('inheritdoc', '')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('delete')
                ->addArgument($lcFirstEntityName, $entityInterfaceName)
                ->withBody($this->getDeleteBody($lcFirstEntityName))
                ->startDocBlockBuilding()
                    ->addTag('inheritdoc', '')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('deleteById')
                ->addArgument($idArgumentName)
                ->withBody($this->getDeleteByIdBody($idArgumentName))
                ->startDocBlockBuilding()
                    ->addTag('inheritdoc', '')
                ->finishBuilding()
            ->finishBuilding();

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($classBuilder->build());

        return new GeneratorResult(
            $fileGenerator->generate(),
            sprintf('%s/Model/%sRepository.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $currentClassName
        );
    }

    private function getConstructorBody(string $entityFactoryClassArgumentName): string
    {
        return "\$this->resource = \$resource;\n"
            . "\$this->$entityFactoryClassArgumentName = \$$entityFactoryClassArgumentName;\n"
            . "\$this->collectionFactory = \$collectionFactory;\n";
    }

    private function getSaveMethodBody(string $lcFirstEntityName): string
    {
        return "try {\n"
            . "    \$this->resource->save(\$$lcFirstEntityName);\n"
            . "} catch (\\Exception \$exception) {\n"
            . "    throw new \\Magento\\Framework\\Exception\\CouldNotSaveException(__(\$exception->getMessage()));\n"
            . "}\n\n"
            . "return \$$lcFirstEntityName;";
    }

    private function getGetByIdBody(
        string $idArgumentName,
        string $entityFactoryClassArgumentName,
        string $lcFirstEntityName
    ): string {
        $ucFirstEntityName = ucfirst($lcFirstEntityName);

        return "\$$lcFirstEntityName = \$this->{$entityFactoryClassArgumentName}->create();\n"
            . "\$this->resource->load(\$$lcFirstEntityName, \$$idArgumentName);\n"
            . "if (!\${$lcFirstEntityName}->getId()) {\n"
            . "    throw new NoSuchEntityException(__('$ucFirstEntityName with id \"%1\" does not exist.', \$$idArgumentName));\n"
            . "}\n\n"
            . "return \$$lcFirstEntityName;";
    }

    private function getFindByIdBody(
        string $idArgumentName,
        string $entityFactoryClassArgumentName,
        string $lcFirstEntityName
    ): string {

        return "\$$lcFirstEntityName = \$this->{$entityFactoryClassArgumentName}->create();\n"
            . "\$this->resource->load(\$$lcFirstEntityName, \$$idArgumentName);\n"
            . "if (!\${$lcFirstEntityName}->getId()) {\n"
            . "    return null;\n"
            . "}\n\n"
            . "return \$$lcFirstEntityName;";
    }

    private function getGetListMethodBody(): string
    {
        return "\$collection = \$this->collectionFactory->create();\n"
            . "foreach (\$searchCriteria->getFilterGroups() as \$filterGroup) {\n"
            . "    foreach (\$filterGroup->getFilters() as \$filter) {\n"
            . "        \$condition = \$filter->getConditionType() ?: 'eq';\n"
            . "        \$collection->addFieldToFilter(\$filter->getField(), [\$condition => \$filter->getValue()]);\n"
            . "    }\n"
            . "}\n\n"
            . "\$sortOrders = \$searchCriteria->getSortOrders();\n"
            . "if (\$sortOrders) {\n"
            . "    foreach (\$sortOrders as \$sortOrder) {\n"
            . "        \$collection->addOrder(\n"
            . "             \$sortOrder->getField(),\n"
            . "             (\$sortOrder->getDirection() === SortOrder::SORT_ASC) ? 'ASC' : 'DESC'\n"
            . "        );\n"
            . "    }\n"
            . "}\n"
            . "\$collection->setCurPage(\$searchCriteria->getCurrentPage());\n"
            . "\$collection->setPageSize(\$searchCriteria->getPageSize());\n\n"
            . "return \$collection->getItems();";
    }

    private function getDeleteBody(string $lcFirstEntityName): string
    {
        return "try {\n"
            . "    \$this->resource->delete(\$$lcFirstEntityName);\n"
            . "} catch (\\Exception \$exception) {\n"
            . "    throw new CouldNotDeleteException(__(\$exception->getMessage()));\n"
            . "}\n\n"
            . 'return true;';
    }

    private function getDeleteByIdBody(string $idArgumentName): string
    {
        return "return \$this->delete(\$this->getById(\$$idArgumentName));";
    }
}
