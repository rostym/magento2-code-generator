<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\CodeTemplate;

use Krifollk\CodeGenerator\Api\CodeTemplateEngineInterface;
use Krifollk\CodeGenerator\Api\TemplateResolverInterface;

/**
 * Class Engine
 *
 * @package Krifollk\CodeGenerator\Model\CodeTemplate
 */
class Engine implements CodeTemplateEngineInterface
{
    const REQUIRED_VARIABLE_REGEX = '~\{\{\s*(.*?)\s*\}\}~';

    /** @var TemplateResolverInterface */
    private $templateResolver;

    /**
     * Engine constructor.
     *
     * @param TemplateResolverInterface $templateResolver
     */
    public function __construct(TemplateResolverInterface $templateResolver)
    {
        $this->templateResolver = $templateResolver;
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function render(string $template, array $variables = []): string
    {
        $resolvedTemplate = $this->templateResolver->resolve($template);

        $content = file_get_contents($resolvedTemplate);

        if ($content === false) {
            throw new \RuntimeException(sprintf('Something went wrong while reading %s template.', $resolvedTemplate));
        }

        $variablesOfTemplate = $this->extractRequiredVariables($content);

        $missedVariables = array_diff($variablesOfTemplate, array_keys($variables));

        if (count($missedVariables) !== 0) {
            throw new NotEnoughVariablesPassedException($missedVariables, $resolvedTemplate);
        }

        return $this->applyVariables($variables, $variablesOfTemplate, $content);
    }

    private function extractRequiredVariables(string $content): array
    {
        $matches = [];
        preg_match_all(self::REQUIRED_VARIABLE_REGEX, $content, $matches);

        if (!isset($matches[1])) {
            return [];
        }

        return array_unique($matches[1]);
    }

    private function applyVariables(array $variables, array $variablesOfTemplate, string $content): string
    {
        $patterns = [];
        $replacements = [];

        foreach ($variablesOfTemplate as $variable) {
            $patterns[] = str_replace('(.*?)', $variable, self::REQUIRED_VARIABLE_REGEX);
            $replacements[] = $variables[$variable];
        }

        /** @var string|null $result */
        $result = preg_replace($patterns, $replacements, $content);

        if ($result === null) {
            throw new \RuntimeException('Something went wrong while applying variables to template.');
        }

        return $result;
    }
}
