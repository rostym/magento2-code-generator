<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad\Repository;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\Triad\CollectionPart;
use Krifollk\CodeGenerator\Model\Generator\Triad\Model;
use Krifollk\CodeGenerator\Model\Generator\Triad\ResourcePart;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\GenericTag;
use Magento\Framework\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

/**
 * Class RepositoryPart
 *
 * @todo    Need refactor
 * @package Krifollk\CodeGenerator\Model\Generator\Triad\Repository
 */
class RepositoryPart extends AbstractRepositoryPart
{
    /**#@+
     * Default class 'use'
     */
    const API_SORT_ORDER_USE                       = 'Magento\Framework\Api\SortOrder';
    const EXCEPTION_NO_SUCH_ENTITY_EXCEPTION_USE   = 'Magento\Framework\Exception\NoSuchEntityException';
    const EXCEPTION_COULD_NOT_DELETE_EXCEPTION_USE = 'Magento\Framework\Exception\CouldNotDeleteException';
    /**#@-*/

    /**#@+
     * Patterns
     */
    const REPOSITORY_NAME_PATTERN         = '\%s\Model\%sRepository';
    const REPOSITORY_FILE_NAME_PATTERN    = '%s/Model/%sRepository.php';
    const REPOSITORY_PACKAGE_NAME_PATTERN = '%s\Model';
    /**#@-*/

    /**
     * Generate entity
     *
     * @return GeneratorResultInterface
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function generate()
    {
        $entityGenerator = $this->createEntityGenerator();
        $entityGenerator->setName($this->generateEntityName(self::REPOSITORY_NAME_PATTERN));
        $entityGenerator->addUse(self::SEARCH_CRITERIA_INTERFACE_NAME);
        $entityGenerator->setDocBlock($this->createClassDocBlock());
        $entityGenerator->addUse(self::API_SORT_ORDER_USE);
        $entityGenerator->addUse(self::EXCEPTION_NO_SUCH_ENTITY_EXCEPTION_USE);
        $entityGenerator->addUse(self::EXCEPTION_COULD_NOT_DELETE_EXCEPTION_USE);
        $entityGenerator->addProperty('resource', null, PropertyGenerator::FLAG_PROTECTED);
        $entityFactory = lcfirst($this->entityName) . 'Factory';
        $entityGenerator->addProperty("$entityFactory", null, PropertyGenerator::FLAG_PROTECTED);
        $entityGenerator->addProperty('collectionFactory', null, PropertyGenerator::FLAG_PROTECTED);

        $entityGenerator->addMethodFromGenerator($this->createConstructor());
        $entityGenerator->addMethodFromGenerator($this->createSaveMethod());
        $entityGenerator->addMethodFromGenerator($this->createGetByIdMethod());
        $entityGenerator->addMethodFromGenerator($this->createGetListMethod());
        $entityGenerator->addMethodFromGenerator($this->createDeleteMethod());
        $entityGenerator->addMethodFromGenerator($this->createDeleteByIdMethod());

        return new GeneratorResult(
            $this->wrapToFile($entityGenerator)->generate(),
            $this->generateFilePath(self::REPOSITORY_FILE_NAME_PATTERN),
            $this->generateEntityName(self::REPOSITORY_NAME_PATTERN)
        );
    }

    /**
     * @return ClassGenerator
     */
    protected function createEntityGenerator()
    {
        return new ClassGenerator();
    }

    /**
     * @return DocBlockGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createClassDocBlock()
    {
        $docBlock = new DocBlockGenerator();
        $docBlock->setWordWrap(false);
        $docBlock->setShortDescription('Class ' . $this->entityName);

        $docBlock->setTag((new GenericTag())
            ->setName('package')
            ->setContent($this->generatePackageName(self::REPOSITORY_PACKAGE_NAME_PATTERN)));

        return $docBlock;
    }

    /**
     * @return MethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createConstructor()
    {
        $method = $this->createMethodGenerator();
        $method->setName('__construct');
        $method->setVisibility(MethodGenerator::VISIBILITY_PUBLIC);
        $entityFactory = lcfirst($this->entityName) . 'Factory';
        $method->setParameter([
            'name' => 'resource',
            'type' => $this->generateEntityName(ResourcePart::RESOURCE_MODEL_NAME_PATTERN),
        ]);
        $method->setParameter([
            'name' => $entityFactory,
            'type' => $this->generateEntityName(Model::MODEL_NAME_PATTERN) . 'Factory',
        ]);
        $method->setParameter([
            'name' => 'collectionFactory',
            'type' => $this->generateEntityName(CollectionPart::COLLECTION_NAME_PATTERN) . 'Factory',
        ]);

        $body = "\$this->resource = \$resource;\n"
            . "\$this->$entityFactory = \$$entityFactory;\n"
            . "\$this->collectionFactory = \$collectionFactory;\n";

        $method->setBody($body);

        return $method;
    }

    /**
     * @return MethodGenerator
     */
    public function createMethodGenerator()
    {
        return new MethodGenerator();
    }

    /**
     * @return \Magento\Framework\Code\Generator\InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createSaveMethod()
    {
        $method = parent::createSaveMethod();
        $paramName = lcfirst($this->entityName);

        $body = "try {\n"
            . "    \$this->resource->save(\$$paramName);\n"
            . "} catch (\\Exception \$exception) {\n"
            . "    throw new \\Magento\\Framework\\Exception\\CouldNotSaveException(__(\$exception->getMessage()));\n"
            . "}\n\n"
            . "return \$$paramName;";

        $method->setBody($body);

        return $method;
    }

    /**
     * @return \Magento\Framework\Code\Generator\InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createGetByIdMethod()
    {
        $method = parent::createGetByIdMethod();
        $entityFactoryName = lcfirst($this->entityName) . 'Factory';
        $paramName = lcfirst($this->entityName);
        $methodParam = lcfirst($this->entityName) . 'Id';

        $body = "\$$paramName = \$this->{$entityFactoryName}->create();\n"
            . "\$this->resource->load(\$$paramName, \$$methodParam);\n"
            . "if (!\${$paramName}->getId()) {\n"
            . "    throw new NoSuchEntityException(__('$this->entityName with id \"%1\" does not exist.', \$$methodParam));\n"
            . "}\n\n"
            . "return \$$paramName;";

        $method->setBody($body);

        return $method;
    }

    /**
     * @return \Magento\Framework\Code\Generator\InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createGetListMethod()
    {
        $method = parent::createGetListMethod();

        $body = "\$collection = \$this->collectionFactory->create();\n"
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
            . "             (\$sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'\n"
            . "        );\n"
            . "    }\n"
            . "}\n"
            . "\$collection->setCurPage(\$searchCriteria->getCurrentPage());\n"
            . "\$collection->setPageSize(\$searchCriteria->getPageSize());\n\n"
            . "return \$collection->getItems();";

        $method->setBody($body);

        return $method;
    }

    /**
     * @return \Magento\Framework\Code\Generator\InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createDeleteMethod()
    {
        $method = parent::createDeleteMethod();
        $paramName = lcfirst($this->entityName);
        $body = "try {\n"
            . "    \$this->resource->delete(\$$paramName);\n"
            . "} catch (\\Exception \$exception) {\n"
            . "    throw new CouldNotDeleteException(__(\$exception->getMessage()));\n"
            . "}\n\n"
            . 'return true;';

        $method->setBody($body);

        return $method;
    }

    /**
     * @return \Magento\Framework\Code\Generator\InterfaceMethodGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createDeleteByIdMethod()
    {
        $method = parent::createDeleteByIdMethod();
        $methodParam = lcfirst($this->entityName) . 'Id';
        $body = "return \$this->delete(\$this->getById(\$$methodParam));";

        $method->setBody($body);

        return $method;
    }
}
