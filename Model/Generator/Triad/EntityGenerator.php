<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
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
 * Class Model
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
class EntityGenerator extends AbstractGenerator
{
    const MODEL_NAME_PATTERN      = '\%s\Model\%s';
    const PACKAGE_NAME_PATTERN    = '%s\Model';
    const MODEL_FILE_NAME_PATTERN = '%s/Model/%s.php';

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
            'entityInterface',
            'tableDescriberResult',
            'resourceEntityName',
            'entityCollectionName',
        ];
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
        $resourceEntityName = $additionalArguments['resourceEntityName'];
        $entityCollectionName = $additionalArguments['entityCollectionName'];
        $entityInterface = $additionalArguments['entityInterface'];

        $fullEntityName = sprintf(
            self::MODEL_NAME_PATTERN,
            $moduleNameEntity->asPartOfNamespace(),
            $entityName
        );

        $gettersSetters = '';
        $columnsCount = count($tableDescriberResult->columns());
        $counter = 1;
        foreach ($tableDescriberResult->columns() as $column) {
            $camelizeColumnName = NameUtil::camelize($column->name());
            $gettersSetters .= $this->codeTemplateEngine->render('entity/model/getterSetter', [
                    'name'       => $camelizeColumnName,
                    'constField' => strtoupper($column->name()),
                ]
            );

            if ($counter !== $columnsCount) {
                $gettersSetters .= "\n\n";
            }

            $counter++;
        }

        return new GeneratorResult(
            $this->codeTemplateEngine->render('entity/model', [
                    'namespace'       => sprintf(self::PACKAGE_NAME_PATTERN, $moduleNameEntity->asPartOfNamespace()),
                    'resourceModel'   => $resourceEntityName,
                    'eventPrefix'     => mb_strtolower(sprintf('%s_model_%s', $moduleNameEntity->value(), $entityName)),
                    'entityName'      => $entityName,
                    'collection'      => $entityCollectionName,
                    'gettersSetters'  => $gettersSetters,
                    'entityInterface' => $entityInterface

                ]
            ),
            sprintf(self::MODEL_FILE_NAME_PATTERN, $moduleNameEntity->asPartOfPath(), $entityName),
            $fullEntityName
        );
    }
}
