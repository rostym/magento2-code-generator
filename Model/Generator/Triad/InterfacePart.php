<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad;

use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\GenericTag;
use Krifollk\CodeGenerator\Model\InterfaceGenerator;
use Krifollk\CodeGenerator\Model\TableInfo;
use Krifollk\CodeGenerator\Model\TableInfoFactory;
use Magento\Framework\Code\Generator\InterfaceMethodGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;

/**
 * Class ModelInterface
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
class InterfacePart extends AbstractPart
{
    const MODEL_INTERFACE_NAME_PATTERN      = '\%s\Api\Data\%sInterface';
    const MODEL_INTERFACE_FILE_NAME_PATTERN = '%s/Api/Data/%sInterface.php';
    const PACKAGE_NAME_PATTERN              = '%s\Api\Data';

    /**
     * Table info factory
     *
     * @var TableInfoFactory
     */
    private $tableInfoFactory;

    /**
     * @var string
     */
    private $tableName;

    /**
     * Model constructor.
     *
     * @param TableInfoFactory $tableInfoFactory
     * @param string           $moduleName
     * @param string           $tableName
     * @param string           $entityName
     */
    public function __construct($moduleName, $entityName, $tableName, TableInfoFactory $tableInfoFactory)
    {
        parent::__construct($moduleName, $entityName);
        $this->tableName = $tableName;
        $this->tableInfoFactory = $tableInfoFactory;
    }

    /**
     * Generate entity
     *
     * @param array $arguments
     *
     * @return GeneratorResult
     */
    public function generate(array $arguments = [])
    {
        $interfaceGenerator = $this->createEntityGenerator();
        $interfaceGenerator->setName($this->generateEntityName(self::MODEL_INTERFACE_NAME_PATTERN));
        $interfaceGenerator->setDocBlock($this->createClassDocBlock());
        $tableInfo = $this->getTableInfo($this->tableName);
        $itemsCount = $tableInfo->getItemsCount();

        for ($i = 0; $i < $itemsCount; $i++) {
            $this->addGetter($tableInfo, $i, $interfaceGenerator);
            $this->addSetter($tableInfo, $i, $interfaceGenerator);
            $this->addConstant($interfaceGenerator, $tableInfo, $i);
        }

        return new GeneratorResult(
            $this->wrapToFile($interfaceGenerator)->generate(),
            $this->generateFilePath(self::MODEL_INTERFACE_FILE_NAME_PATTERN),
            $this->generateEntityName(self::MODEL_INTERFACE_NAME_PATTERN)
        );
    }

    /**
     * @return InterfaceGenerator
     */
    protected function createEntityGenerator()
    {
        return new InterfaceGenerator();
    }

    /**
     * @return DocBlockGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function createClassDocBlock()
    {
        $docBlock = new DocBlockGenerator();
        $docBlock->setWordWrap(false);
        $docBlock->setShortDescription('Interface ' . $this->entityName);

        $docBlock->setTag((new GenericTag())
            ->setName('package')
            ->setContent($this->generatePackageName(self::PACKAGE_NAME_PATTERN)));

        return $docBlock;
    }

    /**
     * @param $tableName
     *
     * @return TableInfo
     */
    protected function getTableInfo($tableName)
    {
        return $this->tableInfoFactory->create(['tableName' => (string)$tableName]);
    }

    /**
     * Add getter
     *
     * @param TableInfo          $tableInfo
     * @param int                $i
     * @param InterfaceGenerator $interfaceGenerator
     *
     * @return $this
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    protected function addGetter($tableInfo, $i, $interfaceGenerator)
    {
        $method = new InterfaceMethodGenerator();
        $method->setName($tableInfo->getGetters()[$i]->getName());
        $method->setBody($tableInfo->getGetters()[$i]->getBody());
        $method->setVisibility(MethodGenerator::VISIBILITY_PUBLIC);
        $method->setDocBlock($tableInfo->getGetters()[$i]->getDocBlock());
        $interfaceGenerator->addMethodFromGenerator($method);

        return $this;
    }

    /**
     * Add setter
     *
     * @param TableInfo          $tableInfo
     * @param int                $i
     * @param InterfaceGenerator $interfaceGenerator
     *
     * @return $this
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    protected function addSetter($tableInfo, $i, $interfaceGenerator)
    {
        $method = new InterfaceMethodGenerator();
        $method->setName($tableInfo->getSetters()[$i]->getName());
        $method->setBody($tableInfo->getSetters()[$i]->getBody());
        $method->setDocBlock($tableInfo->getSetters()[$i]->getDocBlock());
        $method->setParameter(['name' => $tableInfo->getSetters()[$i]->getParameterName()]);
        $method->setVisibility(MethodGenerator::VISIBILITY_PUBLIC);
        $interfaceGenerator->addMethodFromGenerator($method);

        return $this;
    }

    /**
     * @param TableInfo          $tableInfo
     * @param int                $i
     * @param InterfaceGenerator $interfaceGenerator
     *
     * @return $this
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function addConstant($interfaceGenerator, $tableInfo, $i)
    {
        $interfaceGenerator->addConstant(
            $tableInfo->getConstants()[$i]->getName(),
            $tableInfo->getConstants()[$i]->getValue()
        );

        return $this;
    }
}
