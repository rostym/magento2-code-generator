<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command;

use Krifollk\CodeGenerator\Model\Generator\Crud\Layout\IndexFactory;
use Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\ListingFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\CollectionPartFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\InterfacePartFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\ModelFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryInterfacePartFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryPartFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\ResourcePartFactory;

/**
 * Class Crud
 *
 * @package Krifollk\CodeGenerator\Model\Command
 */
class Crud extends Triad
{
    /**
     * @var ListingFactory
     */
    private $listingFactory;

    /**
     * @var IndexFactory
     */
    private $indexFactory;

    public function __construct(
        ModelFactory $modelFactory,
        InterfacePartFactory $interfacePartFactory,
        ResourcePartFactory $resourcePartFactory,
        CollectionPartFactory $collectionPartFactory,
        RepositoryInterfacePartFactory $repositoryInterfacePartFactory,
        RepositoryPartFactory $repositoryPart,
        ListingFactory $listingFactory,
        IndexFactory $indexFactory,
        \Magento\Framework\Filesystem\Driver\File $file
    ) {
        parent::__construct(
            $modelFactory,
            $interfacePartFactory,
            $resourcePartFactory,
            $collectionPartFactory,
            $repositoryInterfacePartFactory,
            $repositoryPart,
            $file
        );
        $this->listingFactory = $listingFactory;
        $this->indexFactory   = $indexFactory;
    }

    /**
     * @inheritdoc
     */
    protected function prepareEntities($moduleName, $tableName, $entityName)
    {
        $entities = parent::prepareEntities($moduleName, $tableName, $entityName);

        $entities['ui_component_listing'] = $this->createUiComponentListingPart($moduleName, $tableName, $entityName)->generate();
        $entities['adminhtml_controller_index'] = $this->createIndexController($moduleName, $entityName)->generate();

        return $entities;
    }

    /**
     * @param $moduleName
     * @param $entityName
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml\Index
     */
    protected function createIndexController($moduleName, $entityName)
    {
        return $this->indexFactory->create([
            'moduleName' => $moduleName,
            'entityName' => $entityName,
        ]);
    }

    /**
     * @param $moduleName
     * @param $tableName
     * @param $entityName
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\Listing
     */
    protected function createUiComponentListingPart($moduleName, $tableName, $entityName)
    {
        return $this->listingFactory->create([
            'tableName'  => $tableName,
            'moduleName' => $moduleName,
            'entityName' => $entityName,
        ]);
    }
}
