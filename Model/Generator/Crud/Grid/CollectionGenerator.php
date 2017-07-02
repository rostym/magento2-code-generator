<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Grid;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class Collection
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Grid
 */
class CollectionGenerator extends AbstractGenerator
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
        return ['entityName'];
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

        $className = sprintf(
            '\%s\Model\ResourceModel\%s\Grid\Collection',
            $moduleNameEntity->asPartOfNamespace(),
            $entityName
        );

        return new GeneratorResult(
            $this->codeTemplateEngine->render('crud/grid/collection', [
                    'namespace' => sprintf(
                        '%s\Model\ResourceModel\%s\Grid',
                        $moduleNameEntity->asPartOfNamespace(),
                        $entityName
                    ),
                ]
            ),
            sprintf('%s/Model/ResourceModel/%s/Grid/Collection.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $className
        );
    }
}
