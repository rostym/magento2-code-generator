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
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class SearchResultInterfaceGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class SearchResultInterfaceGenerator extends AbstractGenerator
{
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
        return ['entityName', 'entityInterface'];
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
        $entityInterface = $additionalArguments['entityInterface'];
        $primaryFieldName = $additionalArguments['primaryFieldName'];

        $className = sprintf(
            '\%s\Api\Data\%sSearchResultsInterface',
            $moduleNameEntity->asPartOfNamespace(),
            $entityName
        );

        return new GeneratorResult(
            $this->codeTemplateEngine->render('entity/searchResultInterface', [
                    'namespace'        => sprintf('%s\Api\Data', $moduleNameEntity->asPartOfNamespace()),
                    'entityName'       => $entityName,
                    'entityInterface'  => $entityInterface,
                    'primaryFieldName' => $primaryFieldName
                ]
            ),
            sprintf('%s/Api/Data/%sSearchResultsInterface.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $className
        );
    }
}
