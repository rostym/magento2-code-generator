<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\Listing\Column;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\TableDescriber\Result;

/**
 * Class EntityActions
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\Listing\Column
 */
class EntityActionsGenerator extends AbstractGenerator
{
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
        return ['entityName', 'tableDescriberResult'];
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
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];

        $className = sprintf(
            '\%s\Model\UiComponent\Listing\Column\%sActions',
            $moduleNameEntity->asPartOfNamespace(),
            $entityName
        );

        $deleteUrl = $this->getActionUrl($moduleNameEntity, $entityName, 'delete');
        $editUrl = $this->getActionUrl($moduleNameEntity, $entityName, 'edit');

        return new GeneratorResult(
            $this->codeTemplateEngine->render('crud/uiComponent\listing\column\actions', [
                    'namespace'     => sprintf('%s\Model\UiComponent\Listing\Column', $moduleNameEntity->asPartOfNamespace()),
                    'entityName'    => ucfirst($entityName),
                    'idFieldName'   => $tableDescriberResult->primaryColumn()->name(),
                    'editUrlPath'   => $editUrl,
                    'deleteUrlPath' => $deleteUrl
                ]
            ),
            sprintf(
                '%s/Model/UiComponent/Listing/Column/%sActions.php',
                $moduleNameEntity->asPartOfPath(),
                $entityName
            ),
            $className
        );
    }

    private function getActionUrl(ModuleNameEntity $moduleName, string $entityName, string $action): string
    {
        return sprintf('%s/%s/%s', mb_strtolower($moduleName->value()), mb_strtolower($entityName), $action);
    }
}
