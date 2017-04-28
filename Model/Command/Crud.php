<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command;

use Krifollk\CodeGenerator\Model\Generator\Crud\Model\DataProviderGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\Listing\Column\EntityActionsGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\CollectionGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\EntityGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\EntityInterfaceGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryInterfaceGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\ResourceGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\TableDescriber;

/**
 * Class Crud
 *
 * @package Krifollk\CodeGenerator\Model\Command
 */
class Crud extends Triad
{
    /** @var EntityActionsGenerator */
    private $entityActionsGenerator;

    /** @var DataProviderGenerator */
    private $dataProviderGenerator;

    public function __construct(
        EntityGenerator $entityGenerator,
        EntityInterfaceGenerator $interfaceGenerator,
        ResourceGenerator $resourceGenerator,
        CollectionGenerator $collectionGenerator,
        RepositoryInterfaceGenerator $repositoryInterfaceGenerator,
        RepositoryGenerator $repositoryGenerator,
        \Magento\Framework\Filesystem\Driver\File $file,
        TableDescriber $tableDescriber,
        EntityActionsGenerator $entityActionsGenerator,
        DataProviderGenerator $dataProviderGenerator
    ) {
        parent::__construct(
            $entityGenerator,
            $interfaceGenerator,
            $resourceGenerator,
            $collectionGenerator,
            $repositoryInterfaceGenerator,
            $repositoryGenerator,
            $file,
            $tableDescriber
        );
        $this->entityActionsGenerator = $entityActionsGenerator;
        $this->dataProviderGenerator = $dataProviderGenerator;
    }

    protected function prepareEntities(
        ModuleNameEntity $moduleName,
        string $tableName,
        string $entityName
    ): GeneratorResult\Container {
        $resultContainer = parent::prepareEntities($moduleName, $tableName, $entityName);

        /** @var TableDescriber\Result $tableDescriberResult */
        $tableDescriberResult = $this->tableDescriber->describe($tableName);

        $dataPersistorEntityKey = mb_strtolower($moduleName->value()) .'_'. mb_strtolower($entityName);

        $resultContainer->insert('crud_entity_actions', $this->entityActionsGenerator->generate($moduleName, [
            'entityName'           => $entityName,
            'tableDescriberResult' => $tableDescriberResult
        ]));

        $resultContainer->insert('crud_data_provider', $this->dataProviderGenerator->generate($moduleName, [
            'entityName'             => $entityName,
            'collectionClassName'    => $resultContainer->get('collection')->getEntityName(),
            'dataPersistorEntityKey' => $dataPersistorEntityKey
        ]));

        return $resultContainer;
    }
}
