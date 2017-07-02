<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Test\Integration\Generator\Module;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\Module\ModuleXml;


/**
 * Class ModuleXmlTest
 *
 * @package Krifollk\CodeGenerator\Test\Integration\Generator\Module
 */
class ModuleXmlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test module.xml file generator
     *
     * @param array $inputData
     * @param array $resultData
     *
     * @test
     * @dataProvider dataProvider
     */
    public function generate(array $inputData, array $resultData)
    {
        $moduleXmlGenerator = new ModuleXml($inputData['moduleName']);
        $result = $moduleXmlGenerator->generate();

        self::assertInstanceOf(GeneratorResultInterface::class, $result);
        self::assertEquals($resultData['content'], $result->getContent());
        self::assertEquals($resultData['entityName'], $result->getEntityName());
        self::assertContains($resultData['destinationFile'], $result->getDestinationFile());
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return require __DIR__ . '/_files/moduleXml.php';
    }
}
