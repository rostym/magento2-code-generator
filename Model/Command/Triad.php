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
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\Generator\Triad\CollectionGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\DiGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\EntityInterfaceGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\EntityGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryInterfaceGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryGenerator;
use Krifollk\CodeGenerator\Model\Generator\Triad\ResourceGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\TableDescriber;

/**
 * Class Triad
 *
 * @package Krifollk\CodeGenerator\Model\Command
 */
class Triad extends AbstractCommand
{
    /** @var EntityGenerator */
    private $entityGenerator;

    /** @var EntityInterfaceGenerator */
    private $interfaceGenerator;

    /** @var ResourceGenerator */
    private $resourceGenerator;

    /** @var CollectionGenerator */
    private $collectionGenerator;

    /** @var RepositoryInterfaceGenerator */
    private $repositoryInterfaceGenerator;

    /** @var RepositoryGenerator */
    private $repositoryGenerator;

    /** @var TableDescriber */
    protected $tableDescriber;

    /** @var DiGenerator */
    private $diGenerator;

    public function __construct(
        EntityGenerator $entityGenerator,
        EntityInterfaceGenerator $interfaceGenerator,
        ResourceGenerator $resourceGenerator,
        CollectionGenerator $collectionGenerator,
        RepositoryInterfaceGenerator $repositoryInterfaceGenerator,
        RepositoryGenerator $repositoryGenerator,
        DiGenerator $diGenerator,
        \Magento\Framework\Filesystem\Driver\File $file,
        TableDescriber $tableDescriber,
        ModulesDirProviderInterface $modulesDirProvider
    ) {
        $this->entityGenerator = $entityGenerator;
        $this->interfaceGenerator = $interfaceGenerator;
        $this->resourceGenerator = $resourceGenerator;
        $this->collectionGenerator = $collectionGenerator;
        $this->repositoryInterfaceGenerator = $repositoryInterfaceGenerator;
        $this->repositoryGenerator = $repositoryGenerator;
        $this->tableDescriber = $tableDescriber;
        $this->diGenerator = $diGenerator;
        parent::__construct($file, $modulesDirProvider);
    }

    /**
     * Generate triad
     *
     * @param ModuleNameEntity $moduleName
     * @param string           $tableName
     * @param string           $entityName
     *
     * @return \Generator
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function generate(ModuleNameEntity $moduleName, string $tableName, string $entityName)
    {
        /** @var GeneratorResult[] $entities */
        $entities = $this->prepareEntities($moduleName, $tableName, $entityName);

        return $this->generateFiles($entities);
    }

    /**
     * @param ModuleNameEntity $moduleName
     * @param string           $tableName
     * @param string           $entityName
     *
     * @return GeneratorResult\Container
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function prepareEntities(
        ModuleNameEntity $moduleName,
        string $tableName,
        string $entityName
    ): GeneratorResult\Container {
        $resultContainer = $this->createResultContainer();
        /** @var TableDescriber\Result $tableDescriberResult */
        $tableDescriberResult = $this->tableDescriber->describe($tableName);

        $resultContainer->insert('entity_interface', $this->interfaceGenerator->generate($moduleName, [
                'entityName'           => $entityName,
                'tableDescriberResult' => $tableDescriberResult
            ]
        ));

        $resultContainer->insert('resource', $this->resourceGenerator->generate($moduleName, [
                'tableDescriberResult' => $tableDescriberResult,
                'entityName'           => $entityName
            ]
        ));

        $resultContainer->insert('entity', $this->entityGenerator->generate($moduleName, [
                'entityName'           => $entityName,
                'entityInterface'      => $resultContainer->get('entity_interface')->getEntityName(),
                'tableDescriberResult' => $tableDescriberResult,
                'resourceEntityName'   => $resultContainer->get('resource')->getEntityName(),
                'entityCollectionName' => NameUtil::generateCollectionName($moduleName, $entityName),
            ]
        ));

        $resultContainer->insert('collection', $this->collectionGenerator->generate($moduleName, [
                'entityName'    => $entityName,
                'resourceClass' => $resultContainer->get('resource')->getEntityName(),
                'modelClass'    => $resultContainer->get('entity')->getEntityName()
            ]
        ));

        $resultContainer->insert('repository_interface', $this->repositoryInterfaceGenerator->generate($moduleName, [
            'entityName'          => $entityName,
            'entityInterfaceName' => $resultContainer->get('entity_interface')->getEntityName()
        ]));

        $resultContainer->insert('repository', $this->repositoryGenerator->generate($moduleName, [
            'entityName'              => $entityName,
            'entityInterfaceName'     => $resultContainer->get('entity_interface')->getEntityName(),
            'resourceEntityName'      => $resultContainer->get('resource')->getEntityName(),
            'entityCollectionName'    => $resultContainer->get('collection')->getEntityName(),
            'repositoryInterfaceName' => $resultContainer->get('repository_interface')->getEntityName()
        ]));

        $resultContainer->insert('di', $this->diGenerator->generate($moduleName, [
            'entityClass'         => $resultContainer->get('entity')->getEntityName(),
            'entityInterface'     => $resultContainer->get('entity_interface')->getEntityName(),
            'repository'          => $resultContainer->get('repository')->getEntityName(),
            'repositoryInterface' => $resultContainer->get('repository_interface')->getEntityName()
        ]));

        return $resultContainer;
    }
}
