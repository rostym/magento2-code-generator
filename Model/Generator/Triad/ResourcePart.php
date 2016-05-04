<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\GenericTag;
use Krifollk\CodeGenerator\Model\TableInfo;
use Krifollk\CodeGenerator\Model\TableInfoFactory;
use Magento\Framework\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;

/**
 * Class ResourcePart
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class ResourcePart extends AbstractPart
{
    const RESOURCE_MODEL_NAME_PATTERN         = '\%s\Model\ResourceModel\%s';
    const RESOURCE_MODEL_PACKAGE_NAME_PATTERN = '\%s\Model\ResourceModel';
    const RESOURCE_MODEL_FILE_NAME_PATTERN    = '%s/Model/ResourceModel/%s.php';
    const ABSTRACT_RESOURCE_CLASS             = '\Magento\Framework\Model\ResourceModel\Db\AbstractDb';
    const CONSTRUCT_BODY_PATTERN              = '$this->_init(\'%s\', \'%s\');';

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var TableInfoFactory
     */
    private $tableInfoFactory;

    /**
     * ResourcePart constructor.
     *
     * @param string           $moduleName
     * @param string           $entityName
     * @param string           $tableName
     * @param TableInfoFactory $tableInfoFactory
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
     * @return GeneratorResultInterface
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function generate()
    {
        $classGenerator = $this->createEntityGenerator();
        $classGenerator->setName($this->generateEntityName(self::RESOURCE_MODEL_NAME_PATTERN));
        $classGenerator->setExtendedClass(self::ABSTRACT_RESOURCE_CLASS);
        $classGenerator->setDocBlock($this->createClassDocBlock());
        $this->addConstruct($classGenerator);

        return new GeneratorResult(
            $this->wrapToFile($classGenerator)->generate(),
            $this->generateFilePath(self::RESOURCE_MODEL_FILE_NAME_PATTERN),
            $this->generateEntityName(self::RESOURCE_MODEL_NAME_PATTERN)
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
            ->setContent($this->generatePackageName(self::RESOURCE_MODEL_PACKAGE_NAME_PATTERN)));

        return $docBlock;
    }

    /**
     * Add _construct method
     *
     * @param ClassGenerator $classGenerator
     *
     * @return $this
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function addConstruct(ClassGenerator $classGenerator)
    {
        $classGenerator->addMethod(
            '_construct',
            [],
            MethodGenerator::FLAG_PROTECTED,
            sprintf(
                self::CONSTRUCT_BODY_PATTERN,
                $this->tableName,
                $this->getTableInfo($this->tableName)->getIdFieldName()
            )
        );

        return $this;
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
}
