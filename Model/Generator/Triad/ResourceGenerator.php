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
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\TableDescriber\Result;

/**
 * Class ResourcePart
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class ResourceGenerator extends AbstractGenerator
{
    const RESOURCE_MODEL_FILE_NAME_PATTERN    = '%s/Model/ResourceModel/%s.php';

    /** @var \Krifollk\CodeGenerator\Model\CodeTemplate\Engine */
    private $codeTemplateEngine;

    /**
     * RepositoryGenerator constructor.
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
        return ['tableDescriberResult', 'entityName'];
    }

    /**
     * @inheritdoc
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
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

        return new GeneratorResult(
            $this->codeTemplateEngine->render('entity/resource', [
                    'namespace'    => sprintf('%s\Model\ResourceModel', $moduleNameEntity->asPartOfNamespace()),
                    'tableName'    => $tableDescriberResult->tableName(),
                    'primaryField' => $this->getPrimaryFieldName($tableDescriberResult, $exposedMessagesContainer),
                    'entityName'   => $entityName

                ]
            ),
            sprintf(self::RESOURCE_MODEL_FILE_NAME_PATTERN, $moduleNameEntity->asPartOfPath(), $entityName),
            $className,
            $exposedMessagesContainer
        );
    }

    private function getPrimaryFieldName(Result $tableDescriberResult, array &$exposedMessagesContainer): string
    {
        $primaryFieldName = '';

        try {
            $primaryFieldName = $tableDescriberResult->primaryColumn()->name();
        } catch (\RuntimeException $e) {
            $exposedMessagesContainer[] = 'Primary column not found. Resource model will be generated with errors.';
        }

        return $primaryFieldName;
    }
}
