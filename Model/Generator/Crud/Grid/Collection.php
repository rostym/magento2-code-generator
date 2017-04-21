<?php

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Grid;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Magento\Framework\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;

/**
 * Class Collection
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Grid
 */
class Collection extends AbstractGenerator
{
    const ABSTRACT_COLLECTION_CLASS    = \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult::class;
    const GRID_COLLECTION_NAME_PATTERN = '\%s\Model\ResourceModel\%s\Grid\Collection';
    const COLLECTION_FILE_NAME_PATTERN = '%s/Model/ResourceModel/%s/Grid/Collection.php';

    /** @var string */
    private $entityName;

    public function __construct($moduleName, $entityName)
    {
        parent::__construct($moduleName);
        $this->entityName = $entityName;
    }

    /**
     * Generate entity
     *
     * @param array $arguments
     *
     * @return GeneratorResultInterface
     */
    public function generate(array $arguments = [])
    {
        $classGenerator = new ClassGenerator();
        $classGenerator->setName($this->generateEntityName(self::GRID_COLLECTION_NAME_PATTERN));
        $classGenerator->setExtendedClass('\\' . self::ABSTRACT_COLLECTION_CLASS);

        return new GeneratorResult(
            $this->wrapToFile($classGenerator)->generate(),
            $this->generateFilePath(self::COLLECTION_FILE_NAME_PATTERN),
            $this->generateEntityName(self::GRID_COLLECTION_NAME_PATTERN)
        );
    }

    /**
     * Generate entity name
     *
     * @param string $pattern
     *
     * @return string
     */
    protected function generateEntityName($pattern)
    {
        return sprintf($pattern, $this->normalizeModuleName($this->moduleName), $this->entityName);
    }

    /**
     * Wrap
     *
     * @param ClassGenerator $generatorObject
     *
     * @return FileGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function wrapToFile($generatorObject)
    {
        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($generatorObject);

        return $fileGenerator;
    }

    /**
     * Generate file path
     *
     * @param string $pattern
     *
     * @return string
     */
    protected function generateFilePath($pattern)
    {
        return $this->getBasePath()
            . '/app/code/'
            . sprintf($pattern, str_replace('_', '/', $this->moduleName), $this->entityName);
    }

    /**
     * Get base path
     *
     * @return string
     */
    protected function getBasePath()
    {
        return BP;
    }
}
