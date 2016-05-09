<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad\Repository;

use Krifollk\CodeGenerator\Model\Generator\Triad\AbstractPart;
use Krifollk\CodeGenerator\Model\GenericTag;
use Magento\Framework\Code\Generator\InterfaceMethodGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;

/**
 * Class AbstractRepositoryPart
 *
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad\Repository
 */
abstract class AbstractRepositoryPart extends AbstractPart
{
    const SEARCH_CRITERIA_INTERFACE_NAME = 'Magento\Framework\Api\SearchCriteriaInterface';

    /**
     * @var string
     */
    protected $modelInterfaceName;

    /**
     * Model constructor.
     *
     * @param string $moduleName
     * @param string $entityName
     * @param string $modelInterfaceName
     */
    public function __construct($moduleName, $entityName, $modelInterfaceName)
    {
        parent::__construct($moduleName, $entityName);
        $this->modelInterfaceName = $modelInterfaceName;
    }

    /**
     * @return InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createSaveMethod()
    {
        $method = $this->createMethodGenerator();
        $method->setName('save');
        $method->setVisibility(MethodGenerator::VISIBILITY_PUBLIC);
        $method->setParameter(['name' => lcfirst($this->entityName), 'type' => $this->modelInterfaceName]);

        $docBLock = new DocBlockGenerator();
        $docBLock->setShortDescription('Save ' . $this->entityName);
        $docBLock->setTag((new GenericTag('param', $this->modelInterfaceName . ' $' . lcfirst($this->entityName))));
        $docBLock->setTag((new GenericTag()));
        $docBLock->setTag((new GenericTag('return', $this->modelInterfaceName)));
        $docBLock->setTag((new GenericTag('throws', '\Magento\Framework\Exception\CouldNotSaveException')));
        $method->setDocBlock($docBLock);

        return $method;
    }

    /**
     * @return \Zend\Code\Generator\MethodGenerator
     */
    abstract public function createMethodGenerator();

    /**
     * @return InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createGetByIdMethod()
    {
        $method = $this->createMethodGenerator();
        $method->setName('getById');
        $method->setVisibility(MethodGenerator::VISIBILITY_PUBLIC);
        $method->setParameter(['name' => lcfirst($this->entityName) . 'Id']);

        $docBLock = new DocBlockGenerator();
        $docBLock->setShortDescription('Retrieve ' . $this->entityName);
        $docBLock->setTag((new GenericTag('param', 'int $' . lcfirst($this->entityName) . 'Id')));
        $docBLock->setTag((new GenericTag()));
        $docBLock->setTag((new GenericTag('return', $this->modelInterfaceName)));
        $docBLock->setTag((new GenericTag('throws', '\Magento\Framework\Exception\NoSuchEntityException')));
        $method->setDocBlock($docBLock);

        return $method;
    }

    /**
     * @return InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createGetListMethod()
    {
        $method = $this->createMethodGenerator();
        $method->setName('getList');
        $method->setVisibility(MethodGenerator::VISIBILITY_PUBLIC);
        $method->setParameter(['name' => 'searchCriteria', 'type' => 'SearchCriteriaInterface']);

        $docBLock = new DocBlockGenerator();
        $docBLock->setShortDescription('Retrieve entity matching the specified criteria.');
        $docBLock->setTag((new GenericTag('param', 'SearchCriteriaInterface $searchCriteria')));
        $docBLock->setTag((new GenericTag()));
        $docBLock->setTag((new GenericTag('return', $this->modelInterfaceName . '[]')));
        $docBLock->setTag((new GenericTag('throws', '\Magento\Framework\Exception\LocalizedException')));
        $method->setDocBlock($docBLock);

        return $method;
    }

    /**
     * @return InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createDeleteMethod()
    {
        $method = $this->createMethodGenerator();
        $method->setName('delete');
        $method->setVisibility(MethodGenerator::VISIBILITY_PUBLIC);
        $method->setParameter(['name' => lcfirst($this->entityName), 'type' => $this->modelInterfaceName]);

        $docBLock = new DocBlockGenerator();
        $docBLock->setShortDescription('Delete ' . $this->entityName);
        $docBLock->setTag((new GenericTag('param', $this->modelInterfaceName . ' $' . lcfirst($this->entityName))));
        $docBLock->setTag((new GenericTag()));
        $docBLock->setTag((new GenericTag('return', 'bool')));
        $docBLock->setTag((new GenericTag('throws', '\Magento\Framework\Exception\CouldNotDeleteException')));
        $method->setDocBlock($docBLock);

        return $method;
    }

    /**
     * @return InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createDeleteByIdMethod()
    {
        $method = $this->createMethodGenerator();
        $method->setName('deleteById');
        $method->setVisibility(MethodGenerator::VISIBILITY_PUBLIC);
        $method->setParameter(['name' => lcfirst($this->entityName) . 'Id']);

        $docBLock = new DocBlockGenerator();
        $docBLock->setShortDescription('Delete entity by ID.');
        $docBLock->setTag((new GenericTag('param', 'int $' . lcfirst($this->entityName) . 'Id')));
        $docBLock->setTag((new GenericTag()));
        $docBLock->setTag((new GenericTag('return', 'bool')));
        $docBLock->setTag((new GenericTag('throws', '\Magento\Framework\Exception\NoSuchEntityException')));
        $docBLock->setTag((new GenericTag('throws', '\Magento\Framework\Exception\CouldNotDeleteException')));
        $method->setDocBlock($docBLock);

        return $method;
    }
}
