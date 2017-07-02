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
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class ComposerJsonGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Module
 */
class ComposerJsonGenerator extends AbstractGenerator
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
        return ['version'];
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        list($vendorName, $moduleName) = explode('_', $moduleNameEntity->value());

        $content = $this->codeTemplateEngine->render('module/composer', [
            'version'    => $additionalArguments['version'] ?: '0.1.0',
            'namespace'  => sprintf('%s\\\\\\\\', str_replace('\\', '\\\\\\\\', $moduleNameEntity->asPartOfNamespace())),
            'vendorName' => lcfirst($vendorName),
            'moduleName' => strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $moduleName))
        ]);
        $destinationFile = sprintf('%s/composer.json', $moduleNameEntity->asPartOfPath());

        return new GeneratorResult($content, $destinationFile, 'composer.json');
    }
}
