<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml;

use Krifollk\CodeGenerator\Model\CodeTemplate\Engine;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Zend\Code\Generator\FileGenerator;

/**
 * Class AbstractAction
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
abstract class AbstractAction extends AbstractGenerator
{
    /** @var Engine */
    protected $codeTemplateEngine;

    /**
     * IndexActionGenerator constructor.
     *
     * @param Engine $codeTemplateEngine
     */
    public function __construct(Engine $codeTemplateEngine)
    {
        $this->codeTemplateEngine = $codeTemplateEngine;
    }

    protected function requiredArguments(): array
    {
        return ['entityName'];
    }

    protected function wrapToFile($generatorObject)
    {
        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($generatorObject);

        return $fileGenerator;
    }

    protected function generateFilePath(
        ModuleNameEntity $moduleNameEntity,
        string $entityName,
        string $actionName
    ): string {
        return sprintf(
            '%s/Controller/Adminhtml/%s/%s.php',
            $moduleNameEntity->asPartOfPath(),
            $entityName,
            $actionName
        );
    }

    protected function generateEntityName(
        ModuleNameEntity $moduleNameEntity,
        string $entityName,
        string $actionName
    ): string {
        return sprintf(
            '\%s\Controller\Adminhtml\%s\%s',
            $moduleNameEntity->asPartOfNamespace(),
            $entityName,
            $actionName
        );
    }

    protected function generateNamespace(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return sprintf('%s\Controller\Adminhtml\%s', $moduleNameEntity->asPartOfNamespace(), $entityName);
    }
}
