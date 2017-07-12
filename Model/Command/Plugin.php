<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command;

use Krifollk\CodeGenerator\Api\ModulesDirProviderInterface;
use Krifollk\CodeGenerator\Model\Generator\PluginGenerator;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Class Plugin
 *
 * @package Krifollk\CodeGenerator\Model\Command
 */
class Plugin extends AbstractCommand
{
    /** @var \Krifollk\CodeGenerator\Model\Generator\PluginGenerator */
    private $pluginGenerator;

    /**
     * Plugin constructor.
     *
     * @param \Magento\Framework\Filesystem\Driver\File               $file
     * @param \Krifollk\CodeGenerator\Api\ModulesDirProviderInterface $modulesDirProvider
     * @param \Krifollk\CodeGenerator\Model\Generator\PluginGenerator $pluginGenerator
     */
    public function __construct(
        File $file,
        ModulesDirProviderInterface $modulesDirProvider,
        PluginGenerator $pluginGenerator
    ) {
        parent::__construct($file, $modulesDirProvider);
        $this->pluginGenerator = $pluginGenerator;
    }

    /**
     * @param \Krifollk\CodeGenerator\Model\ModuleNameEntity $moduleNameEntity
     * @param array                                          $methods
     * @param string                                         $interceptedClassName
     * @param string                                         $destinationClass
     * @param string                                         $dir
     *
     * @return \Generator
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \InvalidArgumentException
     */
    public function generate(
        ModuleNameEntity $moduleNameEntity,
        array $methods,
        string $interceptedClassName,
        string $destinationClass = '',
        string $dir = ''
    ) {
        $container = $this->createResultContainer();
        $container->insert('plugin_generator',
            $this->pluginGenerator->generate($moduleNameEntity,
                [
                    'methods'              => $methods,
                    'interceptedClassName' => $interceptedClassName,
                    'pluginClass'          => $destinationClass
                ]
            )
        );

        return $this->generateFiles($container, $moduleNameEntity, $dir);
    }
}
