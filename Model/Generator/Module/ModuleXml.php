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

/**
 * Class ModuleXml
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Module
 */
class ModuleXml extends AbstractGenerator
{
    const FILE            = BP . '/app/code/%s/etc/module.xml';
    const DEFAULT_VERSION = '0.1.0';

    /**
     * Module version
     *
     * @var string
     */
    private $version;

    /**
     * ModuleXml constructor.
     *
     * @param string $moduleName
     * @param string $version
     */
    public function __construct($moduleName, $version = '')
    {
        parent::__construct($moduleName);
        $this->version = $version;
    }

    /**
     * Generate entity
     *
     * @param array $arguments
     *
     * @return GeneratorResultInterface
     */
    public function generate(array $arguments = [])
    {
        return new GeneratorResult(
            $this->generateContent(),
            $this->generateFilePath(),
            null
        );
    }

    /**
     * Generate module.xml content
     *
     * @return string
     */
    protected function generateContent()
    {
        $version = $this->version ?: self::DEFAULT_VERSION;
        $moduleName = str_replace('/', '_', $this->moduleName);

        $dom = new \DOMDocument('1.0', 'UTF-8');

        $config = $dom->createElement('config');
        $config->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $config->setAttribute('xsi:noNamespaceSchemaLocation', 'urn:magento:framework:Module/etc/module.xsd');

        $module = $dom->createElement('module');
        $module->setAttribute('name', $moduleName);
        $module->setAttribute('setup_version', $version);

        $config->appendChild($module);

        $dom->appendChild($config);
        $dom->formatOutput = true;

        return $dom->saveXML();
    }

    /**
     * @return string
     */
    protected function generateFilePath()
    {
        return sprintf(self::FILE, $this->moduleName);
    }
}
