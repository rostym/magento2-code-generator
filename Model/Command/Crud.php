<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command;

use Krifollk\CodeGenerator\Api\ModulesDirProviderInterface;
use Krifollk\CodeGenerator\Model\Generator\Crud\Config\Adminhtml\RoutesGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Config\DiGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml\DeleteActionGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml\EditActionGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml\IndexActionGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml\NewActionGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml\SaveActionGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Grid\CollectionGenerator as GridCollectionGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Layout\EditGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Layout\IndexGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Layout\NewLayoutGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\Model\DataProviderGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\FormGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\Listing\Column\EntityActionsGenerator;
use Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\ListingGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\Generator\Triad\CollectionGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\DiGenerator as CrudDiGenerator;
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

    /** @var GridCollectionGenerator */
    private $gridCollectionGenerator;

    /** @var FormGenerator */
    private $formGenerator;

    /** @var ListingGenerator */
    private $listingGenerator;

    /** @var EditGenerator */
    private $editLayoutGenerator;

    /** @var IndexGenerator */
    private $indexLayoutGenerator;

    /** @var NewLayoutGenerator */
    private $newLayoutGenerator;

    /** @var IndexActionGenerator */
    private $indexActionGenerator;

    /** @var RoutesGenerator */
    private $routesGenerator;

    /** @var DiGenerator */
    private $diGenerator;

    /** @var EditActionGenerator */
    private $editActionGenerator;

    /** @var NewActionGenerator */
    private $newActionGenerator;

    /** @var SaveActionGenerator */
    private $saveActionGenerator;

    /** @var DeleteActionGenerator */
    private $deleteActionGenerator;

    public function __construct(
        EntityGenerator $entityGenerator,
        EntityInterfaceGenerator $interfaceGenerator,
        ResourceGenerator $resourceGenerator,
        CollectionGenerator $collectionGenerator,
        RepositoryInterfaceGenerator $repositoryInterfaceGenerator,
        RepositoryGenerator $repositoryGenerator,
        CrudDiGenerator $crudDiGenerator,
        \Magento\Framework\Filesystem\Driver\File $file,
        ModulesDirProviderInterface $modulesDirProvider,
        TableDescriber $tableDescriber,
        EntityActionsGenerator $entityActionsGenerator,
        DataProviderGenerator $dataProviderGenerator,
        GridCollectionGenerator $gridCollectionGenerator,
        ListingGenerator $listingGenerator,
        FormGenerator $formGenerator,
        EditGenerator $editLayoutGenerator,
        IndexGenerator $indexLayoutGenerator,
        NewLayoutGenerator $newLayoutGenerator,
        IndexActionGenerator $indexActionGenerator,
        RoutesGenerator $routesGenerator,
        DiGenerator $diGenerator,
        EditActionGenerator $editActionGenerator,
        NewActionGenerator $newActionGenerator,
        SaveActionGenerator $saveActionGenerator,
        DeleteActionGenerator $deleteActionGenerator
    ) {
        parent::__construct(
            $entityGenerator,
            $interfaceGenerator,
            $resourceGenerator,
            $collectionGenerator,
            $repositoryInterfaceGenerator,
            $repositoryGenerator,
            $crudDiGenerator,
            $file,
            $tableDescriber,
            $modulesDirProvider
        );
        $this->entityActionsGenerator = $entityActionsGenerator;
        $this->dataProviderGenerator = $dataProviderGenerator;
        $this->gridCollectionGenerator = $gridCollectionGenerator;
        $this->formGenerator = $formGenerator;
        $this->listingGenerator = $listingGenerator;
        $this->editLayoutGenerator = $editLayoutGenerator;
        $this->indexLayoutGenerator = $indexLayoutGenerator;
        $this->newLayoutGenerator = $newLayoutGenerator;
        $this->indexActionGenerator = $indexActionGenerator;
        $this->routesGenerator = $routesGenerator;
        $this->diGenerator = $diGenerator;
        $this->editActionGenerator = $editActionGenerator;
        $this->newActionGenerator = $newActionGenerator;
        $this->saveActionGenerator = $saveActionGenerator;
        $this->deleteActionGenerator = $deleteActionGenerator;
    }

    protected function prepareEntities(
        ModuleNameEntity $moduleName,
        string $tableName,
        string $entityName
    ): GeneratorResult\Container {
        $resultContainer = parent::prepareEntities($moduleName, $tableName, $entityName);

        /** @var TableDescriber\Result $tableDescriberResult */
        $tableDescriberResult = $this->tableDescriber->describe($tableName);

        $dataPersistorEntityKey = NameUtil::generateDataPersistorKey($moduleName, $entityName);

        $resultContainer->insert('crud_entity_actions', $this->entityActionsGenerator->generate($moduleName, [
            'entityName'           => $entityName,
            'tableDescriberResult' => $tableDescriberResult
        ]));

        $resultContainer->insert('crud_data_provider', $this->dataProviderGenerator->generate($moduleName, [
            'entityName'             => $entityName,
            'collectionClassName'    => $resultContainer->get('collection')->getEntityName(),
            'dataPersistorEntityKey' => $dataPersistorEntityKey
        ]));

        $resultContainer->insert('crud_grid_collection', $this->gridCollectionGenerator->generate($moduleName, [
            'entityName' => $entityName
        ]));

        $resultContainer->insert('crud_ui_form', $this->formGenerator->generate($moduleName, [
            'entityName'           => $entityName,
            'tableDescriberResult' => $tableDescriberResult,
            'dataProvider'         => $resultContainer->get('crud_data_provider')->getEntityName()
        ]));

        $resultContainer->insert('crud_ui_listing', $this->listingGenerator->generate($moduleName, [
            'entityName'           => $entityName,
            'tableDescriberResult' => $tableDescriberResult,
            'actionsColumnClass'   => $resultContainer->get('crud_entity_actions')->getEntityName()
        ]));

        $resultContainer->insert('crud_layout_edit', $this->editLayoutGenerator->generate($moduleName, [
            'entityName' => $entityName
        ]));

        $resultContainer->insert('crud_layout_index', $this->indexLayoutGenerator->generate($moduleName, [
            'entityName' => $entityName
        ]));

        $resultContainer->insert('crud_layout_new', $this->newLayoutGenerator->generate($moduleName, [
            'entityName' => $entityName
        ]));

        $resultContainer->insert('crud_controller_index', $this->indexActionGenerator->generate($moduleName, [
            'entityName' => $entityName
        ]));

        $resultContainer->insert('crud_adminhtml_routes', $this->routesGenerator->generate($moduleName));

        $resultContainer->insert('crud_di', $this->diGenerator->generate($moduleName, [
            'entityName'           => $entityName,
            'resourceModelName'    => $resultContainer->get('resource')->getEntityName(),
            'gridCollectionClass'  => $resultContainer->get('crud_grid_collection')->getEntityName(),
            'tableDescriberResult' => $tableDescriberResult,
            'entityClass'          => $resultContainer->get('entity')->getEntityName(),
            'entityInterface'      => $resultContainer->get('entity_interface')->getEntityName(),
            'repository'           => $resultContainer->get('repository')->getEntityName(),
            'repositoryInterface'  => $resultContainer->get('repository_interface')->getEntityName()
        ]));

        $resultContainer->insert('crud_controller_edit', $this->editActionGenerator->generate($moduleName, [
            'entityName'       => $entityName,
            'entityRepository' => $resultContainer->get('repository_interface')->getEntityName()
        ]));


        $resultContainer->insert('crud_controller_new', $this->newActionGenerator->generate($moduleName, [
            'entityName' => $entityName
        ]));

        $resultContainer->insert('crud_controller_save', $this->saveActionGenerator->generate($moduleName, [
            'entityName'             => $entityName,
            'entityRepository'       => $resultContainer->get('repository_interface')->getEntityName(),
            'entity'                 => $resultContainer->get('entity_interface')->getEntityName(),
            'dataPersistorEntityKey' => $dataPersistorEntityKey
        ]));

        $resultContainer->insert('crud_controller_delete', $this->deleteActionGenerator->generate($moduleName, [
            'entityName'       => $entityName,
            'entityRepositoryInterface' => $resultContainer->get('repository_interface')->getEntityName()
        ]));


        return $resultContainer;
    }
}
