<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Layout;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\GeneratorResult;

/**
 * Class Index
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Layout
 */
class Index extends AbstractLayout
{
    const FILE = BP . '/app/code/%s/view/adminhtml/layout/%s_%s_index.xml';

    /** @var string */
    private $entityName;

    /**
     * Index constructor.
     *
     * @param string $moduleName
     * @param string $entityName
     */
    public function __construct($moduleName, $entityName)
    {
        parent::__construct($moduleName);
        $this->entityName = $entityName;
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
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $page = $this->generatePage($dom);

        $body = $dom->createElement('body');

        $referenceContainer = $dom->createElement('referenceContainer');
        $referenceContainer->setAttribute('name', 'content');

        $uiComponent = $dom->createElement('uiComponent');
        $uiComponent->setAttribute('name', $this->getUiComponentName());

        $referenceContainer->appendChild($uiComponent);
        $body->appendChild($referenceContainer);
        $page->appendChild($body);

        $dom->appendChild($page);
        $dom->formatOutput = true;

        return new GeneratorResult(
            $dom->saveXML(),
            $this->getDestinationFile(),
            $this->entityName
        );
    }

    /**
     * @return string
     */
    protected function getUiComponentName()
    {
        $moduleName = mb_strtolower(str_replace('/', '_', $this->moduleName));
        $entityName = mb_strtolower($this->entityName);

        return sprintf('%s_%s_listing', $moduleName, $entityName);
    }

    /**
     * @return string
     */
    protected function getDestinationFile()
    {
        $normalizedModuleName = mb_strtolower(str_replace('/', '_', $this->moduleName));
        $entityName = mb_strtolower($this->entityName);

        return sprintf(self::FILE, $this->moduleName, $normalizedModuleName, $entityName);
    }
}
