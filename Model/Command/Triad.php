<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command;

use Krifollk\CodeGenerator\Model\Generator\Triad\CollectionPartFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\InterfacePartFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\ModelFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryInterfacePartFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryPartFactory;
use Krifollk\CodeGenerator\Model\Generator\Triad\ResourcePartFactory;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Magento\Framework\Filesystem\DriverInterface;

/**
 * Class Triad
 *
 * Helper class for generate model triad
 *
 * @package Krifollk\CodeGenerator\Model\Command
 */
class Triad
{
    /**
     * @var ModelFactory
     */
    private $modelFactory;

    /**
     * @var InterfacePartFactory
     */
    private $interfacePartFactory;

    /**
     * @var ResourcePartFactory
     */
    private $resourcePartFactory;

    /**
     * @var CollectionPartFactory
     */
    private $collectionPartFactory;

    /**
     * @var RepositoryInterfacePartFactory
     */
    private $repositoryInterfacePartFactory;

    /**
     * @var RepositoryPartFactory
     */
    private $repositoryPart;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $file;

    /**
     * Triad constructor.
     *
     * @param ModelFactory                              $modelFactory
     * @param InterfacePartFactory                      $interfacePartFactory
     * @param ResourcePartFactory                       $resourcePartFactory
     * @param CollectionPartFactory                     $collectionPartFactory
     * @param RepositoryInterfacePartFactory            $repositoryInterfacePartFactory
     * @param RepositoryPartFactory                     $repositoryPart
     * @param \Magento\Framework\Filesystem\Driver\File $file
     */
    public function __construct(
        ModelFactory $modelFactory,
        InterfacePartFactory $interfacePartFactory,
        ResourcePartFactory $resourcePartFactory,
        CollectionPartFactory $collectionPartFactory,
        RepositoryInterfacePartFactory $repositoryInterfacePartFactory,
        RepositoryPartFactory $repositoryPart,
        \Magento\Framework\Filesystem\Driver\File $file
    ) {
        $this->modelFactory = $modelFactory;
        $this->interfacePartFactory = $interfacePartFactory;
        $this->resourcePartFactory = $resourcePartFactory;
        $this->collectionPartFactory = $collectionPartFactory;
        $this->repositoryInterfacePartFactory = $repositoryInterfacePartFactory;
        $this->repositoryPart = $repositoryPart;
        $this->file = $file;
    }

    /**
     * Generate triad
     *
     * @param string $moduleName
     * @param string $tableName
     * @param string $entityName
     *
     * @return \Generator
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function generate($moduleName, $tableName, $entityName)
    {
        /** @var GeneratorResult[] $entities */
        $entities = [];

        $entities['interface'] = $this
            ->createInterfaceGenerator($tableName, $moduleName, $entityName)
            ->generate();

        $entities['repository'] = $repository = $this
            ->createRepositoryGenerator($moduleName, $entityName, $entities['interface']->getEntityName())
            ->generate();

        $entities['repositoryInterface'] = $this
            ->createRepositoryInterfaceGenerator($moduleName, $entityName, $entities['interface']->getEntityName())
            ->generate();

        $entities['resource'] = $this
            ->createResourceModelGenerator($tableName, $moduleName, $entityName)
            ->generate();

        $entities['model'] = $this
            ->createModelGenerator(
                $tableName,
                $moduleName,
                $entityName,
                $entities['interface']->getEntityName(),
                $entities['resource']->getEntityName()
            )->generate();

        $entities['collection'] = $this
            ->createCollectionGenerator(
                $moduleName,
                $entityName,
                $entities['model']->getEntityName(),
                $entities['resource']->getEntityName()
            )
            ->generate();
        
        return $this->generateFiles($entities);
    }

    /**
     * @param string $tableName
     * @param string $moduleName
     * @param string $entityName
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Triad\InterfacePart
     */
    protected function createInterfaceGenerator($tableName, $moduleName, $entityName)
    {
        return $this->interfacePartFactory->create([
            'tableName'  => $tableName,
            'moduleName' => $moduleName,
            'entityName' => $entityName,
        ]);
    }

    /**
     * @param string $moduleName
     * @param string $entityName
     * @param string $modelInterfaceName
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryPart
     */
    protected function createRepositoryGenerator($moduleName, $entityName, $modelInterfaceName)
    {
        return $this->repositoryPart->create([
            'moduleName'         => $moduleName,
            'entityName'         => $entityName,
            'modelInterfaceName' => $modelInterfaceName,
        ]);
    }

    /**
     * @param string $moduleName
     * @param string $entityName
     * @param string $modelInterfaceName
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryInterfacePart
     */
    protected function createRepositoryInterfaceGenerator(
        $moduleName,
        $entityName,
        $modelInterfaceName
    ) {
        return $this->repositoryInterfacePartFactory->create([
            'moduleName'         => $moduleName,
            'entityName'         => $entityName,
            'modelInterfaceName' => $modelInterfaceName,
        ]);
    }

    /**
     * @param string $tableName
     * @param string $moduleName
     * @param string $entityName
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Triad\ResourcePart
     */
    protected function createResourceModelGenerator($tableName, $moduleName, $entityName)
    {
        return $this->resourcePartFactory->create(
            [
                'tableName'  => $tableName,
                'moduleName' => $moduleName,
                'entityName' => $entityName,
            ]
        );
    }

    /**
     * @param string $tableName
     * @param string $moduleName
     * @param string $entityName
     * @param string $interfaceName
     * @param string $resourceName
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Triad\Model
     */
    protected function createModelGenerator($tableName, $moduleName, $entityName, $interfaceName, $resourceName)
    {
        return $this->modelFactory->create(
            [
                'tableName'     => $tableName,
                'moduleName'    => $moduleName,
                'entityName'    => $entityName,
                'interfaceName' => $interfaceName,
                'resourceName'  => $resourceName,
            ]
        );
    }

    /**
     * @param string $moduleName
     * @param string $entityName
     * @param string $modelClass
     * @param string $resourceClass
     *
     * @return \Krifollk\CodeGenerator\Model\Generator\Triad\CollectionPart
     */
    protected function createCollectionGenerator($moduleName, $entityName, $modelClass, $resourceClass)
    {
        return $this->collectionPartFactory->create(
            [
                'moduleName'    => $moduleName,
                'entityName'    => $entityName,
                'modelClass'    => $modelClass,
                'resourceClass' => $resourceClass,
            ]
        );
    }

    /**
     * @param array $entities
     *
     * @return \Generator
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function generateFiles(array $entities)
    {
        /** @var GeneratorResult $entity */
        foreach ($entities as $entity) {
            $this->file->createDirectory($entity->getDestinationDir(), DriverInterface::WRITEABLE_DIRECTORY_MODE);
            $this->file->filePutContents($entity->getDestinationFile(), $entity->getContent());
            yield $entity->getDestinationFile();
        }
    }
}
