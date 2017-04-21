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
use Krifollk\CodeGenerator\Model\NodeBuilder;

/**
 * Class NewLayout
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Layout
 */
class NewLayout extends AbstractLayout
{
    const FILE = BP . '/app/code/%s/view/adminhtml/layout/%s_%s_new.xml';

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
        $new = new NodeBuilder('page', [
            'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:noNamespaceSchemaLocation' => 'urn:magento:framework:View/Layout/etc/page_configuration.xsd'
        ]);

        $new->elementNode('update', ['handle' => $this->generateEditHandleName()]);

        return new GeneratorResult(
            $new->toXml(),
            $this->getDestinationFile(),
            $this->entityName
        );
    }

    /**
     * @return string
     */
    private function generateEditHandleName()
    {
        $normalizedModuleName = mb_strtolower(str_replace('/', '_', $this->moduleName));
        $entityName = mb_strtolower($this->entityName);

        return sprintf('%s_%s_edit', $normalizedModuleName, $entityName);
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
