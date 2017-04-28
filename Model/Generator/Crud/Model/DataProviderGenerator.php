<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Model;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Zend\Code\Generator\FileGenerator;

/**
 * Class DataProvider
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Model
 */
class DataProviderGenerator extends AbstractGenerator
{
    const NAME_PATTERN      = '\%s\Model\%s\DataProvider';
    const FILE_NAME_PATTERN = '%s/Model/%s/DataProvider.php';

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName', 'collectionClassName', 'dataPersistorEntityKey'];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        $collectionClassName = $additionalArguments['collectionClassName'];
        $dataPersistorEntityKey = $additionalArguments['dataPersistorEntityKey'];

        $className = sprintf('\%s\Model\%s\DataProvider', $moduleNameEntity->asPartOfNamespace(), $entityName);

        $classBuilder = new ClassBuilder($className);

        $classBuilder
            ->extendedFrom('\Magento\Ui\DataProvider\AbstractDataProvider')
            ->startPropertyBuilding('dataPersistor')
                ->markAsPrivate()
            ->finishBuilding()
            ->startPropertyBuilding('loadedData')
                ->markAsPrivate()
                ->defaultValue([])
            ->finishBuilding()
            ->startMethodBuilding('__construct')
                ->markAsPublic()
                ->startArgumentBuilding('name')
                ->finishBuilding()
                ->startArgumentBuilding('primaryFieldName')
                ->finishBuilding()
                ->startArgumentBuilding('requestFieldName')
                ->finishBuilding()
                ->startArgumentBuilding('collectionFactory')
                    ->type(sprintf('%sFactory', $collectionClassName))
                ->finishBuilding()
                ->startArgumentBuilding('dataPersistor')
                    ->type('\Magento\Framework\App\Request\DataPersistorInterface')
                ->finishBuilding()
                ->startArgumentBuilding('meta')
                    ->type('array')
                    ->value([])
                ->finishBuilding()
                ->startArgumentBuilding('data')
                    ->type('array')
                    ->value([])
                ->finishBuilding()
                ->withBody($this->getConstructorBody())
            ->finishBuilding()
            ->startMethodBuilding('getData')
                ->markAsPublic()
                ->withBody($this->getGetDataBody($dataPersistorEntityKey))
            ->finishBuilding();


        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($classBuilder->build());

        return new GeneratorResult(
            $fileGenerator->generate(),
            sprintf('%s/Model/%s/DataProvider.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $className
        );
    }

    private function getConstructorBody():string
    {
        return '$this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);';
    }

    private function getGetDataBody(string $dataPersistorEntityKey): string
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
            $dataPersistorEntityKey
        );
    }
}
