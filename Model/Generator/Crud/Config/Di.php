<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Config;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\Generator\NameUtil;
use Krifollk\CodeGenerator\Model\GeneratorResult;

/**
 * Class Di
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Config
 */
class Di extends AbstractGenerator
{
    const FILE = BP . '/app/code/%s/etc/di.xml';

    /** @var \DOMDocument */
    private $dom;

    /** @var string */
    private $gridCollectionClass;

    /** @var string */
    private $tableName;

    /** @var string */
    private $resourceModelName;
    /**
     * @var string
     */
    private $entityName;

    /**
     * Di constructor.
     *
     * @param string       $entityName
     * @param string $gridCollectionClass
     * @param string $moduleName
     * @param string $tableName
     * @param string $resourceModelName
     *
     * @internal param string $dataProviderName
     */
    public function __construct($entityName, $gridCollectionClass, $moduleName, $tableName, $resourceModelName)
    {
        parent::__construct($moduleName);
        $this->gridCollectionClass = $gridCollectionClass;
        $this->tableName = $tableName;
        $this->resourceModelName = $resourceModelName;
        $this->entityName = $entityName;
    }

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
        $this->dom = new \DOMDocument('1.0', 'UTF-8');

        $config = $this->dom->createElement('config');
        $config->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $config->setAttribute('xsi:noNamespaceSchemaLocation', 'urn:magento:framework:ObjectManager/etc/config.xsd');

        $this->registerDataSource($config);
        $this->registerGridCollection($config);

        $this->dom->appendChild($config);

        $this->dom->formatOutput = true;

        return new GeneratorResult(
            $this->dom->saveXML(),
            $this->getDestinationFile(),
            ''
        );
    }

    /**
     * @param \DOMElement $config
     *
     * @throws \InvalidArgumentException
     */
    protected function registerDataSource(\DOMElement $config)
    {
        $type = $this->dom->createElement('type');
        $type->setAttribute('name', \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory::class);

        $arguments = $this->dom->createElement('arguments');
        $argument = $this->createArgument('collections', 'array');

        $item = $this->dom->createElement('item', $this->gridCollectionClass);
        $item->setAttribute('name', NameUtil::generateDataSourceName(str_replace('/', '_', $this->moduleName), $this->entityName));
        $item->setAttribute('xsi:type', 'string');

        $argument->appendChild($item);
        $arguments->appendChild($argument);
        $type->appendChild($arguments);
        $config->appendChild($type);

    }

    /**
     * @param        $name
     * @param        $type
     * @param string $value
     *
     * @return \DOMElement
     */
    protected function createArgument($name, $type, $value = '')
    {
        $argument = $this->dom->createElement('argument');
        $argument->setAttribute('name', $name);
        $argument->setAttribute('xsi:type', $type);
        $argument->nodeValue = $value;

        return $argument;
    }

    private function registerGridCollection(\DOMElement $config)
    {
        $type = $this->dom->createElement('type');
        $type->setAttribute('name', $this->gridCollectionClass);

        $arguments = $this->dom->createElement('arguments');
        $mainTableArgument = $this->createArgument('mainTable', 'string', $this->tableName);
        $resourceModelArgument = $this->createArgument('resourceModel', 'string', $this->resourceModelName);

        $arguments->appendChild($mainTableArgument);
        $arguments->appendChild($resourceModelArgument);
        $type->appendChild($arguments);

        $config->appendChild($type);
    }

    private function getDestinationFile()
    {
        return sprintf(self::FILE, $this->moduleName);
    }
}
