<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Test\Integration\Generator\Triad;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\Triad\EntityGenerator;
use Krifollk\CodeGenerator\Model\TableInfo;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class ModelTest
 *
 * @package Krifollk\CodeGenerator\Test\Integration\Generator\Triad
 */
class ModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    const  TABLE_NAME = 'all_data_types';
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    /**
     * Test model generation
     *
     * @param array $inputData
     * @param array $resultData
     * @test
     * @dataProvider dataProvider
     */
    public function generate(array $inputData, array $resultData)
    {
        /** @var EntityGenerator $model */
        $model = Bootstrap::getObjectManager()->create(
            EntityGenerator::class,
            [
                'tableName'     => self::TABLE_NAME,
                'moduleName'    => $inputData['moduleName'],
                'entityName'    => $inputData['entityName'],
                'interfaceName' => $inputData['interfaceName'],
                'resourceName'  => $inputData['resourceName'],
            ]
        );

        $result = $model->generate();

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
        return require __DIR__ . '/_files/model.php';
    }

    protected function setUp()
    {
        /** @var ModuleDataSetupInterface $installer */
        $installer = Bootstrap::getObjectManager()->create(ModuleDataSetupInterface::class);
        $this->connection = $installer->getConnection();
        $this->connection->query($this->getTestTableSql());
    }

    /**
     * @return string
     */
    protected function getTestTableSql()
    {
        return 'CREATE TABLE `' . self::TABLE_NAME . '` (
	`id` INT(10) UNSIGNED NOT NULL,
	`varchar` VARCHAR(20) NOT NULL,
	`tinyint` TINYINT(4) NOT NULL,
	`text` TEXT NOT NULL,
	`date` DATE NOT NULL,
	`smallint` SMALLINT(6) NOT NULL,
	`mediumint` MEDIUMINT(9) NOT NULL,
	`int` INT(11) NOT NULL,
	`bigint` BIGINT(20) NOT NULL,
	`float` FLOAT(10,2) NOT NULL,
	`double` DOUBLE NOT NULL,
	`decimal` DECIMAL(10,2) NOT NULL,
	`datetime` DATETIME NOT NULL,
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`time` TIME NOT NULL,
	`year` YEAR NOT NULL,
	`char` CHAR(10) NOT NULL,
	`tinyblob` TINYBLOB NOT NULL,
	`tinytext` TINYTEXT NOT NULL,
	`blob` BLOB NOT NULL,
	`mediumblob` MEDIUMBLOB NOT NULL,
	`mediumtext` MEDIUMTEXT NOT NULL,
	`longblob` LONGBLOB NOT NULL,
	`longtext` LONGTEXT NOT NULL,
	`enum` ENUM(\'1\',\'2\',\'3\') NOT NULL,
	`set` SET(\'1\',\'2\',\'3\') NOT NULL,
	`bool` TINYINT(1) NOT NULL,
	`binary` BINARY(20) NOT NULL,
	`varbinary` VARBINARY(20) NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM
;';
    }

    /**
     * Cleanup DDL cache for the fixture table
     */
    protected function tearDown()
    {
        $this->connection->dropTable(self::TABLE_NAME);
        $this->connection->resetDdlCache(self::TABLE_NAME);
        $this->connection = null;
    }
}
