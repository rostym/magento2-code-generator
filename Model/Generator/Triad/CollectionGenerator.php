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


/**
 * Class CollectionPart
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class CollectionGenerator extends AbstractGenerator
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
        return ['entityName', 'resourceClass', 'modelClass'];
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
        $resourceClass = $additionalArguments['resourceClass'];
        $modelClass = $additionalArguments['modelClass'];
        $className = NameUtil::generateCollectionName($moduleNameEntity, $entityName);

        $namespace = sprintf('%s\Model\ResourceModel\%s', $moduleNameEntity->asPartOfNamespace(), $entityName);
        $eventPrefix = mb_strtolower(sprintf('%s_%s_collection', $moduleNameEntity->value(), $entityName));

        return new GeneratorResult(
            $this->codeTemplateEngine->render('entity/collection', [
                    'namespace'      => $namespace,
                    'entity'         => $modelClass,
                    'entityResource' => $resourceClass,
                    'eventPrefix'    => $eventPrefix
                ]
            ),
            sprintf('%s/Model/ResourceModel/%s/Collection.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $className
        );
    }
}
