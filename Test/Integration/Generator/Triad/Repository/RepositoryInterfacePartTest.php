<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Test\Integration\Generator\Triad\Repository;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryInterfacePart;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class RepositoryInterfacePartTest
 *
 * @package Krifollk\CodeGenerator\Test\Integration\Generator\Triad\Repository
 */
class RepositoryInterfacePartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $inputData
     * @param array $resultData
     *
     * @dataProvider dataProvider
     * @test
     */
    public function generate(array $inputData, array $resultData)
    {
        $repositoryInterfaceGenerator = Bootstrap::getObjectManager()
            ->create(
                RepositoryInterfacePart::class,
                [
                    'moduleName'         => $inputData['moduleName'],
                    'entityName'         => $inputData['entityName'],
                    'modelInterfaceName' => $inputData['modelInterfaceName'],
                ]
            );

        /** @var RepositoryInterfacePart $repositoryInterfaceGenerator */
        $result = $repositoryInterfaceGenerator->generate();

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
        return require __DIR__ . '/_files/repositoryInterfaceData.php';
    }
}
