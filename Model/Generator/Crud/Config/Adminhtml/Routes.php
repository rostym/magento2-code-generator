<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Config\Adminhtml;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;

/**
 * Class Router
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Config\Adminhtml
 */
class Routes extends AbstractGenerator
{
    const FILE = BP . '/app/code/%s/etc/adminhtml/routes.xml';

    /**
     * Generate entity
     *
     * @param ModuleNameEntity|\Krifollk\CodeGenerator\Model\ModuleNameEntity $moduleNameEntity
     * @param array                                                           $additionalArguments
     *
     * @return GeneratorResultInterface
     */
    public function generate(\Krifollk\CodeGenerator\Model\ModuleNameEntity $moduleNameEntity, array $additionalArguments = [])
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');

        $config = $dom->createElement('config');
        $config->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $config->setAttribute('xsi:noNamespaceSchemaLocation', 'urn:magento:framework:App/etc/routes.xsd');

        $router = $dom->createElement('router');
        $router->setAttribute('id', 'admin');

        $route = $dom->createElement('route');
        $route->setAttribute('id', $this->getFrontName());
        $route->setAttribute('frontName', $this->getFrontName());

        $module = $dom->createElement('module');
        $module->setAttribute('name', str_replace('/', '_', $this->moduleName));
        $module->setAttribute('before', 'Magento_Backend');

        $route->appendChild($module);
        $router->appendChild($route);
        $config->appendChild($router);

        $dom->appendChild($config);

        $dom->formatOutput = true;

        return new GeneratorResult(
            $dom->saveXML(),
            $this->getDestinationFile(),
            ''
        );
    }

    protected function getFrontName()
    {
        return strtolower(str_replace('/', '_', $this->moduleName));
    }

    protected function getDestinationFile()
    {
        return sprintf(self::FILE, $this->moduleName);
    }
}
