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
use Krifollk\CodeGenerator\Model\TableInfo;
use Krifollk\CodeGenerator\Model\TableInfoFactory;
use Magento\Framework\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

/**
 * Class Model
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
class Model extends AbstractPart
{
    const MODEL_NAME_PATTERN      = '\%s\Model\%s';
    const PACKAGE_NAME_PATTERN    = '%s\Model';
    const MODEL_FILE_NAME_PATTERN = '%s/Model/%s.php';
    const CONSTRUCT_BODY_PATTERN  = '$this->_init(%s::class);';

    /**
     * Abstract model magento class
     */
    const ABSTRACT_MODEL_MAGENTO_CLASS = '\Magento\Framework\Model\AbstractModel';

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
     * Interface name
     *
     * @var string
     */
    private $interfaceName;

    /**
     * @var string
     */
    private $resourceName;

    /**
     * Model constructor.
     *
     * @param string           $moduleName
     * @param string           $entityName
     * @param string           $interfaceName
     * @param string           $resourceName
     * @param string           $tableName
     * @param TableInfoFactory $tableInfoFactory
     */
    public function __construct(
        $moduleName,
        $entityName,
        $interfaceName,
        $resourceName,
        $tableName,
        TableInfoFactory $tableInfoFactory
    ) {
        parent::__construct($moduleName, $entityName);
        $this->tableName = $tableName;
        $this->interfaceName = $interfaceName;
        $this->resourceName = $resourceName;
        $this->tableInfoFactory = $tableInfoFactory;
    }

    /**
     * Generate entity
     *
     * @return GeneratorResult
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function generate()
    {
        $classGenerator = $this->createEntityGenerator();
        $classGenerator->setName($this->generateEntityName(self::MODEL_NAME_PATTERN));
        $classGenerator->setDocBlock($this->createClassDocBlock());
        $classGenerator->setExtendedClass(self::ABSTRACT_MODEL_MAGENTO_CLASS);
        $classGenerator->setImplementedInterfaces([$this->interfaceName]);
        $classGenerator->addProperty('_eventPrefix', $this->generateEventPrefix(), PropertyGenerator::FLAG_PROTECTED);

        $this->addConstruct($classGenerator);

        $tableInfo = $this->createTableInfo($this->tableName);
        $methodsCount = $tableInfo->getItemsCount();

        for ($i = 0; $i < $methodsCount; $i++) {
            $classGenerator->addMethod(
                $tableInfo->getGetters()[$i]->getName(),
                [],
                MethodGenerator::FLAG_PUBLIC,
                $tableInfo->getGetters()[$i]->getBody(),
                $tableInfo->getGetters()[$i]->getDocBlock()
            );

            $classGenerator->addMethod(
                $tableInfo->getSetters()[$i]->getName(),
                [$tableInfo->getSetters()[$i]->getParameterName()],
                MethodGenerator::FLAG_PUBLIC,
                $tableInfo->getSetters()[$i]->getBody(),
                $tableInfo->getSetters()[$i]->getDocBlock()
            );
        }

        return new GeneratorResult(
            $this->wrapToFile($classGenerator)->generate(),
            $this->generateFilePath(self::MODEL_FILE_NAME_PATTERN),
            $this->generateEntityName(self::MODEL_NAME_PATTERN)
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

        $docBlock->setTag(
            (new GenericTag())
                ->setName('method')
                ->setContent($this->resourceName . ' getResource()')
        );
        $docBlock->setTag(
            (new GenericTag())
                ->setName('method')
                ->setContent($this->generateEntityName(CollectionPart::COLLECTION_NAME_PATTERN) . ' getCollection')
        );
        $docBlock->setTag(
            (new GenericTag())
                ->setName('method')
                ->setContent(
                    $this->generateEntityName(CollectionPart::COLLECTION_NAME_PATTERN) . ' getResourceCollection'
                )
        );

        $docBlock->setTag((new GenericTag())); //empty line
        $docBlock->setTag((new GenericTag())
            ->setName('package')
            ->setContent($this->generatePackageName(self::PACKAGE_NAME_PATTERN)));

        return $docBlock;
    }

    /**
     * @return string
     */
    protected function generateEventPrefix()
    {
        return mb_strtolower(str_replace('/', '_', $this->moduleName) . '_model_' . $this->entityName);
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
            sprintf(self::CONSTRUCT_BODY_PATTERN, $this->resourceName)
        );

        return $this;
    }

    /**
     * @param string $tableName
     *
     * @return TableInfo
     */
    protected function createTableInfo($tableName)
    {
        return $this->tableInfoFactory->create(['tableName' => (string)$tableName]);
    }
}
