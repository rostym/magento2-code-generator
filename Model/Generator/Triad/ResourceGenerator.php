<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\TableDescriber\Result;
use Zend\Code\Generator\FileGenerator;

/**
 * Class ResourcePart
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class ResourceGenerator extends AbstractGenerator
{
    const RESOURCE_MODEL_PACKAGE_NAME_PATTERN = '\%s\Model\ResourceModel';
    const RESOURCE_MODEL_FILE_NAME_PATTERN    = '%s/Model/ResourceModel/%s.php';

    /**
     * Return array of required arguments
     *
     * @return array
     */
    protected function requiredArguments(): array
    {
        return ['tableDescriberResult', 'entityName'];
    }

    /**
     * @inheritdoc
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];

        $exposedMessagesContainer = [];
        $className = NameUtil::generateResourceName($moduleNameEntity, $entityName);
        $classBuilder = new ClassBuilder($className);

        $classBuilder
            ->extendedFrom('\Magento\Framework\Model\ResourceModel\Db\AbstractDb')
            ->startDocBlockBuilding()
                ->shortDescription(sprintf('Class %s', $entityName))
                ->addTag('package', sprintf(self::RESOURCE_MODEL_PACKAGE_NAME_PATTERN, $entityName))
            ->finishBuilding()
            ->startMethodBuilding('_construct')
                ->markAsProtected()
                ->withBody($this->generateConstructMethodBody($tableDescriberResult, $exposedMessagesContainer))
                ->startDocBlockBuilding()
                    ->addTag('inheritdoc', '')
                ->finishBuilding()
            ->finishBuilding();

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($classBuilder->build());

        return new GeneratorResult(
            $fileGenerator->generate(),
            sprintf(self::RESOURCE_MODEL_FILE_NAME_PATTERN, $moduleNameEntity->asPartOfPath(), $entityName),
            $className,
            $exposedMessagesContainer
        );
    }

    private function generateConstructMethodBody(Result $tableDescriberResult, array &$exposedMessagesContainer): string
    {
        $primaryFieldName = '';

        try {
            $primaryFieldName = $tableDescriberResult->primaryColumn()->name();
        } catch (\RuntimeException $e) {
            $exposedMessagesContainer[] = 'Primary column not found. Resource model will be generated with errors.';
        }

        return sprintf("\$this->_init('%s', '%s');", $tableDescriberResult->tableName(), $primaryFieldName);
    }
}
