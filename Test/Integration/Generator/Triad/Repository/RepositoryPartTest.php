<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Test\Integration\Generator\Triad\Repository;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\Triad\Repository\RepositoryPart;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class RepositoryInterfacePartTest
 *
 * @package Krifollk\CodeGenerator\Test\Integration\Generator\Triad\Repository
 */
class RepositoryPartTest extends \PHPUnit_Framework_TestCase
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
        $repositoryGenerator = Bootstrap::getObjectManager()
            ->create(
                RepositoryPart::class,
                [
                    'moduleName'         => $inputData['moduleName'],
                    'entityName'         => $inputData['entityName'],
                    'modelInterfaceName' => $inputData['modelInterfaceName'],
                ]
            );

        /** @var RepositoryPart $repositoryGenerator */
        $result = $repositoryGenerator->generate();

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
        return require __DIR__ . '/_files/repositoryPart.php';
    }
}
