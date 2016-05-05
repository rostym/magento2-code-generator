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
use Magento\Framework\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

/**
 * Class CollectionPart
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class CollectionPart extends AbstractPart
{
    const ABSTRACT_COLLECTION_CLASS = '\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection';

    /**#@+
     * Patterns
     */
    const COLLECTION_NAME_PATTERN      = '\%s\Model\ResourceModel\%s\Collection';
    const COLLECTION_FILE_NAME_PATTERN = '%s/Model/ResourceModel/%s/Collection.php';
    const CONSTRUCT_BODY_PATTERN       = '$this->_init(%s::class, %s::class);';
    const PACKAGE_NAME_PATTERN         = '%s\Model\ResourceModel\\%s\Collection';
    /**#@-*/

    const DOC_BLOCK_TAGS = [
        'method'   => [
            ' getResource()'             => 'resource',
            '[] getItems()'              => 'model',
            '[] getItemsByColumnValue()' => 'model',
            ' getFirstItem()'            => 'model',
            ' getLastItem()'             => 'model',
            ' getItemByColumnValue()'    => 'model',
            ' getItemById()'             => 'model',
            ' getNewEmptyItem()'         => 'model',
            ' fetchItem()'               => 'model',
            ' beforeAddLoadedItem()'     => 'model',
        ],
        'property' => [
            '[] _items'  => 'model',
            ' _resource' => 'resource',
        ],
    ];

    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var string
     */
    private $resourceClass;

    /**
     * ResourcePart constructor.
     *
     * @param string $moduleName
     * @param string $entityName
     * @param string $modelClass
     * @param string $resourceClass
     *
     */
    public function __construct($moduleName, $entityName, $modelClass, $resourceClass)
    {
        parent::__construct($moduleName, $entityName);
        $this->modelClass = $modelClass;
        $this->resourceClass = $resourceClass;
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
        $classGenerator->setName($this->generateEntityName(self::COLLECTION_NAME_PATTERN));
        $classGenerator->setExtendedClass(self::ABSTRACT_COLLECTION_CLASS);
        $classGenerator->setDocBlock($this->createDocBlock());
        $classGenerator->addProperty(
            '_eventPrefix',
            $this->generateEventPrefixName(),
            PropertyGenerator::FLAG_PROTECTED
        );
        $classGenerator->addProperty(
            '_eventObject',
            $this->generateEventObjectName(),
            PropertyGenerator::FLAG_PROTECTED
        );
        $this->addConstruct($classGenerator);

        return new GeneratorResult(
            $this->wrapToFile($classGenerator)->generate(),
            $this->generateFilePath(self::COLLECTION_FILE_NAME_PATTERN),
            $this->generateEntityName(self::COLLECTION_NAME_PATTERN)
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
    protected function createDocBlock()
    {
        $docBlock = new DocBlockGenerator();
        $docBlock->setWordWrap(false);
        $docBlock->setShortDescription('Class ' . $this->entityName);

        $entityType['resource'] = $this->generateEntityName(ResourcePart::RESOURCE_MODEL_NAME_PATTERN);
        $entityType['model'] = $this->generateEntityName(Model::MODEL_NAME_PATTERN);

        foreach (self::DOC_BLOCK_TAGS as $docType => $details) {
            foreach ($details as $content => $type) {
                $docBlock->setTag((new GenericTag())
                    ->setName($docType)
                    ->setContent($entityType[$type] . $content)
                );
            }
            $docBlock->setTag((new GenericTag()));//empty line
        }

        $docBlock->setTag((new GenericTag())
            ->setName('package')
            ->setContent($this->generatePackageName(self::PACKAGE_NAME_PATTERN)));

        return $docBlock;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    protected function generatePackageName($pattern)
    {
        return sprintf($pattern, $this->normalizeModuleName($this->moduleName), $this->entityName);
    }

    /**
     * @return string
     */
    protected function generateEventPrefixName()
    {
        $modulePart = str_replace('/', '_', $this->moduleName);

        return mb_strtolower(sprintf('%s_%s_collection',$modulePart, $this->entityName));
    }

    /**
     * @return string
     */
    protected function generateEventObjectName()
    {
        return $this->generateEventPrefixName() . '_object';
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
                $this->modelClass,
                $this->resourceClass
            )
        );

        return $this;
    }
}
