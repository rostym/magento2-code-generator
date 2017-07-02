<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator;

use DOMDocument;
use Krifollk\CodeGenerator\Api\ModulesDirProviderInterface;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Class AbstractXmlGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
abstract class AbstractXmlGenerator extends AbstractGenerator
{
    const ERROR_PATTERN = '[%s %s] %s (in %s file - line %d)';

    const LIBXML_ERRORS_TYPE_MAP = [
        LIBXML_ERR_FATAL   => 'FATAL',
        LIBXML_ERR_WARNING => 'WARNING',
        LIBXML_ERR_ERROR   => 'ERROR',
    ];

    /** @var ModulesDirProviderInterface */
    protected $modulesDirProvider;

    /** @var File */
    protected $file;

    /**
     * AbstractXmlGenerator constructor.
     *
     * @param File                        $file
     * @param ModulesDirProviderInterface $modulesDirProvider
     */
    public function __construct(File $file, ModulesDirProviderInterface $modulesDirProvider)
    {
        $this->modulesDirProvider = $modulesDirProvider;
        $this->file = $file;
    }

    protected function load(string $file): DOMDocument
    {
        $previousValue = libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        if (!$dom->load($file, LIBXML_COMPACT | LIBXML_NONET)) {
            throw new \InvalidArgumentException(implode("\n", $this->getXmlErrors()));
        }
        libxml_use_internal_errors($previousValue);

        return $dom;
    }

    private function getXmlErrors(): array
    {
        $errors = [];
        /** @var \LibXMLError $error */
        foreach (libxml_get_errors() as $error) {
            $errors[] = sprintf(
                self::ERROR_PATTERN,
                self::LIBXML_ERRORS_TYPE_MAP[$error->level],
                $error->code,
                trim($error->message),
                $error->file ?: 'unknown',
                $error->line
            );
        }

        libxml_clear_errors();

        return $errors;
    }

    protected function getDiConfigFile(ModuleNameEntity $moduleNameEntity): string
    {
        return sprintf(
            '%s/etc/di.xml',
            $moduleNameEntity->asPartOfPath()
        );
    }
}
