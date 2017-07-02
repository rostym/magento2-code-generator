<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\ListingGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class DeleteActionGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
class DeleteActionGenerator extends AbstractAction
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return array_merge(parent::requiredArguments(), ['entityRepositoryInterface']);
    }

    /**
     * @inheritdoc
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        $entityRepositoryInterface = $additionalArguments['entityRepositoryInterface'];

        return new GeneratorResult(
            $this->codeTemplateEngine->render('crud/controller/adminhtml/delete', [
                    'namespace'                 => $this->generateNamespace($moduleNameEntity, $entityName),
                    'entityRepositoryInterface' => $entityRepositoryInterface,
                    'requestIdFiledName'        => ListingGenerator::REQUEST_FIELD_NAME
                ]
            ),
            $this->generateFilePath($moduleNameEntity, $entityName, 'Delete'),
            $this->generateEntityName($moduleNameEntity, $entityName, 'Delete')
        );
    }
}
