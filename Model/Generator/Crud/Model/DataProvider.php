<?php

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Model;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Code\Generator\ClassGenerator;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

/**
 * Class DataProvider
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Model
 */
class DataProvider extends AbstractGenerator
{
    const NAME_PATTERN      = '\%s\Model\%s\DataProvider';
    const FILE_NAME_PATTERN = BP . '/app/code/%s/Model/%s/DataProvider.php';

    /** @var string */
    private $entityName;

    /** @var string */
    private $collectionClassName;

    /**
     * DataProvider constructor.
     *
     * @param string $moduleName
     * @param string $entityName
     * @param string $collectionClassName
     */
    public function __construct($moduleName, $entityName, $collectionClassName)
    {
        parent::__construct($moduleName);
        $this->entityName = $entityName;
        $this->collectionClassName = $collectionClassName;
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
        $classGenerator->setName($this->generateName());
        $classGenerator->setExtendedClass(sprintf('\\%s', AbstractDataProvider::class));
        $this->addConstructor($classGenerator);
        $classGenerator->addProperty('dataPersistor', null, PropertyGenerator::FLAG_PRIVATE);
        $classGenerator->addProperty('loadedData', [], PropertyGenerator::FLAG_PRIVATE);

        $classGenerator->addMethod('getData', [], MethodGenerator::FLAG_PUBLIC, $this->getGetDataBody());

        return new GeneratorResult(
            $this->wrapToFile($classGenerator)->generate(),
            $this->generateFilePath(self::FILE_NAME_PATTERN),
            $this->generateEntityName(self::NAME_PATTERN)
        );
    }

    private function generateName()
    {
        return sprintf(self::NAME_PATTERN, $this->normalizeModuleName($this->moduleName), $this->entityName);
    }

    /**
     * @param ClassGenerator $classGenerator
     *
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    private function addConstructor(ClassGenerator $classGenerator)
    {
        $classGenerator->addMethod(
            '__construct',
            [
                [
                    'name'         => 'name',
                ],
                [
                    'name'         => 'primaryFieldName',
                ],
                [
                    'name'         => 'requestFieldName',
                ],
                [
                    'name'         => 'collectionFactory',
                    'type'         => sprintf('%sFactory', $this->collectionClassName)
                ],
                [
                    'name'         => 'dataPersistor',
                    'type'         => sprintf('\\%s', DataPersistorInterface::class)
                ],
                [
                    'name'         => 'meta',
                    'defaultvalue' => [],
                    'type'         => 'array'
                ],
                [
                    'name'         => 'data',
                    'defaultvalue' => [],
                    'type'         => 'array'
                ],
            ],
            MethodGenerator::FLAG_PUBLIC,
            '$this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);'
        );
    }

    /**
     * Generate file path
     *
     * @param string $pattern
     *
     * @return string
     */
    private function generateFilePath($pattern)
    {
        return sprintf($pattern, str_replace('_', '/', $this->moduleName), $this->entityName);
    }

    /**
     * Generate entity name
     *
     * @param string $pattern
     *
     * @return string
     */
    private function generateEntityName($pattern)
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
    private function wrapToFile($generatorObject)
    {
        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($generatorObject);

        return $fileGenerator;
    }

    /**
     * @return string
     */
    private function getGetDataBody()
    {
        return sprintf(
            '
if ($this->loadedData) {
    return $this->loadedData;
}
$items = $this->collection->getItems();

foreach ($items as $item) {
    $this->loadedData[$item->getId()] = $item->getData();
}

$data = $this->dataPersistor->get(\'%1$s\');
if (!empty($data)) {
    $item = $this->collection->getNewEmptyItem();
    $item->setData($data);
    $this->loadedData[$item->getId()] = $item->getData();
    $this->dataPersistor->clear(\'%1$s\');
}

return $this->loadedData;
            ',
            str_replace('/', '_', mb_strtolower($this->moduleName)) .'_'. mb_strtolower($this->entityName)
        );
    }
}
