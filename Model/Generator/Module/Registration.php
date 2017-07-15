<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Module;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Zend\Code\Generator\FileGenerator;

/**
 * Class Registration
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Module
 */
class Registration extends AbstractGenerator
{
    const FILE = '%s/registration.php';
    const BODY_PATTERN = 'ComponentRegistrar::register(ComponentRegistrar::MODULE, \'%s\', __DIR__);';

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $fileGenerator = new FileGenerator();
        $fileGenerator->setUse(\Magento\Framework\Component\ComponentRegistrar::class);
        $fileGenerator->setBody($this->generateBody($moduleNameEntity));

        return new GeneratorResult(
            $fileGenerator->generate(),
            $this->generateFilePath($moduleNameEntity)
        );
    }

    protected function generateBody(ModuleNameEntity $moduleNameEntity): string
    {
        return sprintf(self::BODY_PATTERN, str_replace('/', '_', $moduleNameEntity->asPartOfPath()));
    }

    protected function generateFilePath(ModuleNameEntity $moduleNameEntity): string
    {
        return sprintf(self::FILE, $moduleNameEntity->asPartOfPath());
    }
}
