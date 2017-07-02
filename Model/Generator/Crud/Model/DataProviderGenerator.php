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
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class DataProvider
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Model
 */
class DataProviderGenerator extends AbstractGenerator
{
    const NAME_PATTERN      = '\%s\Model\%s\DataProvider';
    const FILE_NAME_PATTERN = '%s/Model/%s/DataProvider.php';

    /** @var \Krifollk\CodeGenerator\Model\CodeTemplate\Engine */
    private $codeTemplateEngine;

    /**
     * CollectionGenerator constructor.
     *
     * @param \Krifollk\CodeGenerator\Model\CodeTemplate\Engine $codeTemplateEngine
     */
    public function __construct(\Krifollk\CodeGenerator\Model\CodeTemplate\Engine $codeTemplateEngine)
    {
        $this->codeTemplateEngine = $codeTemplateEngine;
    }

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName', 'collectionClassName', 'dataPersistorEntityKey'];
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        $collectionClassName = $additionalArguments['collectionClassName'];
        $dataPersistorEntityKey = $additionalArguments['dataPersistorEntityKey'];

        $className = sprintf('\%s\Model\%s\DataProvider', $moduleNameEntity->asPartOfNamespace(), $entityName);

        return new GeneratorResult(
            $this->codeTemplateEngine->render('crud/model/dataProvider', [
                    'namespace'         => sprintf('%s\Model\%s', $moduleNameEntity->asPartOfNamespace(), $entityName),
                    'dataPersistorKey'  => $dataPersistorEntityKey,
                    'collectionFactory' => sprintf('%sFactory', $collectionClassName)
                ]
            ),
            sprintf('%s/Model/%s/DataProvider.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $className
        );
    }
}
