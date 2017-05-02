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
use Krifollk\CodeGenerator\Model\Generator\AbstractXmlGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\NodeBuilder;

/**
 * Class DiGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class DiGenerator extends AbstractXmlGenerator
{
    const PREFERENCE_XPATH = "//config/preference[contains(@for,'%s')]";

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityClass', 'entityInterface', 'repository', 'repositoryInterface'];
    }

    /**
     * @inheritdoc
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityClass = ltrim($additionalArguments['entityClass'], '\\');
        $entityInterface = ltrim($additionalArguments['entityInterface'], '\\');
        $repository = ltrim($additionalArguments['repository'], '\\');
        $repositoryInterface = ltrim($additionalArguments['repositoryInterface'], '\\');
        $exposedMessages = [];

        $file = $this->getDiConfigFile($moduleNameEntity);

        if ($this->file->isExists($file)) {
            $domDocument = $this->load($file);

            $nodeBuilder = new NodeBuilder('', [], $domDocument);

            if (!$nodeBuilder->isExistByPath(sprintf(self::PREFERENCE_XPATH, $entityInterface))) {
                $nodeBuilder->elementNode('preference', ['for' => $entityInterface, 'type' => $entityClass]);
                $exposedMessages[] = '';//TODO
            }

            if (!$nodeBuilder->isExistByPath(sprintf(self::PREFERENCE_XPATH, $repositoryInterface))) {
                $nodeBuilder->elementNode('preference', ['for' => $repositoryInterface, 'type' => $repository]);
                $exposedMessages[] = '';//TODO
            }
        } else {
            $nodeBuilder = new NodeBuilder('config', [
                'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:noNamespaceSchemaLocation' => 'urn:magento:framework:ObjectManager/etc/config.xsd'
            ]);

            $nodeBuilder
                ->elementNode('preference', ['for' => $entityInterface, 'type' => $entityClass])
                ->elementNode('preference', ['for' => $repositoryInterface, 'type' => $repository]);
        }

        return new GeneratorResult(
            $nodeBuilder->toXml(),
            $file,
            'di',
            $exposedMessages
        );
    }
}
