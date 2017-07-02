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
 * Class ModelInterface
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
class EntityInterfaceGenerator extends AbstractGenerator
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
        return ['entityName', 'tableDescriberResult'];
    }

    /**
     * @inheritdoc
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];

        $fullEntityName = sprintf(
            '\%s\Api\Data\%sInterface',
            $moduleNameEntity->asPartOfNamespace(),
            $entityName
        );

        $gettersSetters = '';
        $constants = '';
        $columnsCount = count($tableDescriberResult->columns());
        $counter = 1;
        foreach ($tableDescriberResult->columns() as $column) {
            $camelizedColumnName = NameUtil::camelize($column->name());

            $gettersSetters .= $this->codeTemplateEngine->render('entity/interface/getterSetter', [
                    'name' => $camelizedColumnName,
                    'type' => $column->type(),
                ]
            );

            $constants .= sprintf('     const %s = \'%s\';', strtoupper($column->name()), $column->name());

            if ($counter !== $columnsCount) {
                $gettersSetters .= "\n\n";
                $constants .= "\n";
            }

            $counter++;
        }

        return new GeneratorResult(
            $this->codeTemplateEngine->render('entity/interface', [
                    'namespace'      => sprintf('%s\Api\Data', $moduleNameEntity->asPartOfNamespace()),
                    'constants'      => $constants,
                    'entityName'     => $entityName,
                    'gettersSetters' => $gettersSetters,

                ]
            ),
            sprintf('%s/Api/Data/%sInterface.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $fullEntityName
        );
    }
}
