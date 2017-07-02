<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad\Repository;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class RepositoryGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad\Repository
 */
class RepositoryGenerator extends AbstractGenerator
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
        return [
            'entityName',
            'entityInterfaceName',
            'resourceEntityName',
            'entityCollectionName',
            'resourceEntityName',
            'repositoryInterfaceName',
            'searchResultName'
        ];
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
        $entityInterfaceName = $additionalArguments['entityInterfaceName'];
        $resourceEntityName = $additionalArguments['resourceEntityName'];
        $entityCollectionName = $additionalArguments['entityCollectionName'];
        $repositoryInterfaceName = $additionalArguments['repositoryInterfaceName'];
        $searchResultName = $additionalArguments['searchResultName'];
        $currentClassName = sprintf('\%s\Model\%sRepository', $moduleNameEntity->asPartOfNamespace(), $entityName);
        $lcFirstEntityName = lcfirst($entityName);

        return new GeneratorResult(
            $this->codeTemplateEngine->render('entity/repository', [
                    'namespace'                 => sprintf('%s\Model', $moduleNameEntity->asPartOfNamespace()),
                    'entityName'                => $entityName,
                    'entityRepositoryInterface' => $repositoryInterfaceName,
                    'entityCollectionName'      => $entityCollectionName,
                    'entityInterface'           => $entityInterfaceName,
                    'entityResource'            => $resourceEntityName,
                    'entityNameVar'             => $lcFirstEntityName,
                    'searchResultName'          => $searchResultName
                ]
            ),
            sprintf('%s/Model/%sRepository.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $currentClassName
        );
    }
}
