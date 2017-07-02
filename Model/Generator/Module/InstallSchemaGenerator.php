<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Module;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class InstallSchemaGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Module
 */
class InstallSchemaGenerator extends AbstractGenerator
{
    /** @var \Krifollk\CodeGenerator\Model\CodeTemplate\Engine */
    private $codeTemplateEngine;

    /**
     * IndexActionGenerator constructor.
     *
     * @param Engine $codeTemplateEngine
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
        return [];
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $content = $this->codeTemplateEngine->render('module/installSchema', [
            'namespace' => sprintf('%s\Setup', $moduleNameEntity->asPartOfNamespace())
        ]);
        $destinationFile = sprintf('%s/Setup/InstallSchema.php', $moduleNameEntity->asPartOfPath());
        $entityName = sprintf('\%s\Setup\InstallSchema', $moduleNameEntity->asPartOfNamespace());

        return new \Krifollk\CodeGenerator\Model\GeneratorResult(
            $content,
            $destinationFile,
            $entityName
        );
    }
}
