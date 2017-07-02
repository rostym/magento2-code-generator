<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Test\Integration\Generator\Triad;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\Triad\CollectionGenerator;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class CollectionPartTest
 *
 * @package Krifollk\CodeGenerator\Test\Integration\Generator\Triad
 */
class CollectionPartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Collection file generator
     *
     * @param array $inputData
     * @param array $resultData
     *
     * @test
     * @dataProvider dataProvider
     */
    public function generate(array $inputData, array $resultData)
    {
        $collectionGenerator = Bootstrap::getObjectManager()
            ->create(
                CollectionGenerator::class,
                [
                    'moduleName'    => $inputData['moduleName'],
                    'entityName'    => $inputData['entityName'],
                    'modelClass'    => $inputData['modelClass'],
                    'resourceClass' => $inputData['resourceClass'],
                ]
            );

        /** @var CollectionGenerator $collectionGenerator */
        $result = $collectionGenerator->generate();

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
        return require __DIR__ . '/_files/collectionPart.php';
    }
}
