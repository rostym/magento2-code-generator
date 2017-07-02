<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\CodeTemplate;

use Krifollk\CodeGenerator\Api\TemplateResolverInterface;

/**
 * Class TemplateResolver
 *
 * @package Krifollk\CodeGenerator\Model\CodeTemplate
 */
class TemplateResolver implements TemplateResolverInterface
{
    const FILE_EXTENSION = 'pct';

    /** @var \Magento\Framework\Module\Dir\Reader */
    private $moduleDirReader;

    /**
     * TemplateResolver constructor.
     *
     * @param \Magento\Framework\Module\Dir\Reader $moduleDirReader
     */
    public function __construct(\Magento\Framework\Module\Dir\Reader $moduleDirReader)
    {
        $this->moduleDirReader = $moduleDirReader;
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function resolve(string $template): string
    {
        $fullPathToTemplate = sprintf('%s%s.%s', $this->getTemplateDir(), $template, self::FILE_EXTENSION);

        if (!file_exists($fullPathToTemplate)) {
            throw new \RuntimeException(sprintf('%s template not exist.', $fullPathToTemplate));
        }

        return $fullPathToTemplate;
    }

    private function getTemplateDir(): string
    {
        return $this->moduleDirReader->getModuleDir('etc', 'Krifollk_CodeGenerator')
            . DIRECTORY_SEPARATOR
            . self::TEMPLATES_DIRECTORY
            . DIRECTORY_SEPARATOR;
    }
}
