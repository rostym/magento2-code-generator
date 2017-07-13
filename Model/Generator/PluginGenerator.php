<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\CodeTemplate\Engine;
use Krifollk\CodeGenerator\Model\MethodInjector;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class PluginGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
class PluginGenerator extends AbstractGenerator
{
    /** @var \Krifollk\CodeGenerator\Model\MethodInjector */
    private $methodInjector;

    /** @var \Krifollk\CodeGenerator\Model\CodeTemplate\Engine */
    private $codeTemplateEngine;

    /**
     * PluginGenerator constructor.
     *
     * @param \Krifollk\CodeGenerator\Model\MethodInjector      $methodInjector
     * @param \Krifollk\CodeGenerator\Model\CodeTemplate\Engine $codeTemplateEngine
     */
    public function __construct(MethodInjector $methodInjector, Engine $codeTemplateEngine)
    {
        $this->methodInjector = $methodInjector;
        $this->codeTemplateEngine = $codeTemplateEngine;
    }

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['methods', 'interceptedClassName'];
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     * @throws \ReflectionException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        /** @var \Krifollk\CodeGenerator\Model\Command\Plugin\Method[] $methods */
        $methods = $additionalArguments['methods'];
        $pluginClass = $additionalArguments['pluginClass'];
        $interceptedClassName = $additionalArguments['interceptedClassName'];

        $pluginFullClassName = $this->generatePluginFullClassName(
            $moduleNameEntity,
            $pluginClass,
            $interceptedClassName
        );

        $interceptors = '';
        $reflectionClass = new \ReflectionClass($interceptedClassName);

        foreach ($methods as $method) {
            $methodName = ucfirst($method->getMethodName());
            $methodParameters = $reflectionClass->getMethod($method->getMethodName())->getParameters();

            $parameters = '';
            foreach ($methodParameters as $parameter) {
                if ($parameter->getType() === null) {
                    $parameters .= sprintf(
                        ', $%s%s',
                        $parameter->getName(),
                        $this->prepareDefaultParameterValue($parameter, $interceptedClassName)
                    );
                    continue;
                }

                $parameters .= sprintf(
                    ', %s%s $%s%s',
                    $parameter->getType()->isBuiltin() ? '' : '\\',//Add back slash to class
                    $parameter->getType(),
                    $parameter->getName(),
                    $this->prepareDefaultParameterValue($parameter, $interceptedClassName)

                );
            }

            if ($method->isRequireBeforeInterceptor()) {
                $interceptors .= $this->codeTemplateEngine->render('plugin/before_method', [
                    'methodName' => $methodName,
                    'subject'    => $interceptedClassName,
                    'arguments'  => count($methodParameters) > 0 ? $parameters : ''
                ]);
                $interceptors .= "\n";
            }

            if ($method->isRequireAroundInterceptor()) {
                $interceptors .= $this->codeTemplateEngine->render('plugin/around_method', [
                    'methodName' => $methodName,
                    'subject'    => $interceptedClassName,
                    'arguments'  => count($methodParameters) > 0 ? $parameters : ''
                ]);
                $interceptors .= "\n";
            }

            if ($method->isRequireAfterInterceptor()) {
                $interceptors .= $this->codeTemplateEngine->render('plugin/after_method', [
                    'methodName' => $methodName,
                    'subject'    => $interceptedClassName,
                    'arguments'  => ''//TODO in magento 2.2 we can pass arguments to after interceptor
                ]);
                $interceptors .= "\n";
            }
        }

        $explodedClassName = explode('\\', $pluginFullClassName);
        $shortClassName = array_pop($explodedClassName);
        $namespace = trim(str_replace($shortClassName, '', $pluginFullClassName), '\\');

        $content = $this->generatePluginContent($namespace, $shortClassName, $interceptors, $pluginFullClassName);

        $destinationFile = sprintf('%s.php', str_replace('\\', '/', trim($pluginFullClassName, '\\')));

        return new \Krifollk\CodeGenerator\Model\GeneratorResult(
            $content,
            $destinationFile,
            $pluginFullClassName
        );
    }

    private function generatePluginFullClassName(
        ModuleNameEntity $moduleNameEntity,
        string $pluginClass,
        string $interceptedClassName
    ): string {
        if ($pluginClass === '') {
            return sprintf('\%s\Plugin\%s', $moduleNameEntity->asPartOfNamespace(), trim($interceptedClassName, '\\'));
        }

        return sprintf('\%s\%s', $moduleNameEntity->asPartOfNamespace(), $pluginClass);
    }

    /**
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    private function generatePluginContent(
        string $namespace,
        string $shortClassName,
        string $interceptors,
        string $pluginFullClassName
    ): string {
        if (class_exists($pluginFullClassName)) {
            $pluginReflectionClass = new \ReflectionClass($pluginFullClassName);
            $content = file_get_contents($pluginReflectionClass->getFileName());
            $content = $this->methodInjector->inject($content, $interceptors);

            return $content;
        }

        $content = $this->codeTemplateEngine->render('plugin/class', [
            'namespace'    => $namespace,
            'className'    => $shortClassName,
            'interceptors' => $interceptors
        ]);

        return $content;
    }

    private function prepareDefaultParameterValue(\ReflectionParameter $parameter, string $interceptedClass): string
    {
        $value = '';
        if (!$parameter->isDefaultValueAvailable()) {
            return $value;
        }

        $pattern = ' = %s';
        if ($parameter->isDefaultValueConstant()) {
            if (strpos($parameter->getDefaultValueConstantName(), 'self::') !== false) {
                return sprintf($pattern,
                    str_replace('self::', $interceptedClass . '::', $parameter->getDefaultValueConstantName()));
            }

            return sprintf($pattern, '\\' . $parameter->getDefaultValueConstantName());
        }

        return sprintf($pattern, var_export($parameter->getDefaultValue(), true));
    }
}
