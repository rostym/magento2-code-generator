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


use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class IndexActionGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
class IndexActionGenerator extends AbstractAction
{
    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];

        return new GeneratorResult(
            $this->codeTemplateEngine->render('crud/controller/adminhtml/index', [
                    'namespace' => $this->generateNamespace($moduleNameEntity, $entityName)
                ]
            ),
            $this->generateFilePath($moduleNameEntity, $entityName, 'Index'),
            $this->generateEntityName($moduleNameEntity, $entityName, 'Index')
        );
    }
}
