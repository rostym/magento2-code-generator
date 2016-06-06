<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Krifollk\CodeGenerator\Test\Integration\Generator\Triad\ModelTest;

return [
    [
        [ // input data
            'moduleName'    => 'Test/Module',
            'entityName'    => 'Test',
            'tableName'     => ModelTest::TABLE_NAME,
            'interfaceName' => '\Test\Module\Api\Data\Test',
            'resourceName'  => '\Test\Module\Model\ResourceModel\Test',
        ],
        [// generated data
            'destinationFile' => 'app/code/Test/Module/Model/Test.php',
            'entityName'      => '\Test\Module\Model\Test',
            'content'         => '<?php

namespace Test\Module\Model;

/**
 * Class Test
 *
 * @method \Test\Module\Model\ResourceModel\Test getResource()
 * @method \Test\Module\Model\ResourceModel\Test\Collection getCollection
 * @method \Test\Module\Model\ResourceModel\Test\Collection getResourceCollection
 *
 * @package Test\Module\Model
 */
class Test extends \Magento\Framework\Model\AbstractModel implements \Test\Module\Api\Data\Test
{

    protected $_eventPrefix = \'test_module_model_test\';

    protected function _construct()
    {
        $this->_init(\Test\Module\Model\ResourceModel\Test::class);
    }

    /**
     * Get id value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_getData(self::ID);
    }

    /**
     * Set id value.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->setData(self::ID, $id);

        return $this;
    }

    /**
     * Get varchar value.
     *
     * @return string
     */
    public function getVarchar()
    {
        return $this->_getData(self::VARCHAR);
    }

    /**
     * Set varchar value.
     *
     * @param string $varchar
     *
     * @return $this
     */
    public function setVarchar($varchar)
    {
        $this->setData(self::VARCHAR, $varchar);

        return $this;
    }

    /**
     * Get tinyint value.
     *
     * @return int
     */
    public function getTinyint()
    {
        return $this->_getData(self::TINYINT);
    }

    /**
     * Set tinyint value.
     *
     * @param int $tinyint
     *
     * @return $this
     */
    public function setTinyint($tinyint)
    {
        $this->setData(self::TINYINT, $tinyint);

        return $this;
    }

    /**
     * Get text value.
     *
     * @return string
     */
    public function getText()
    {
        return $this->_getData(self::TEXT);
    }

    /**
     * Set text value.
     *
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->setData(self::TEXT, $text);

        return $this;
    }

    /**
     * Get date value.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->_getData(self::DATE);
    }

    /**
     * Set date value.
     *
     * @param string $date
     *
     * @return $this
     */
    public function setDate($date)
    {
        $this->setData(self::DATE, $date);

        return $this;
    }

    /**
     * Get smallint value.
     *
     * @return int
     */
    public function getSmallint()
    {
        return $this->_getData(self::SMALLINT);
    }

    /**
     * Set smallint value.
     *
     * @param int $smallint
     *
     * @return $this
     */
    public function setSmallint($smallint)
    {
        $this->setData(self::SMALLINT, $smallint);

        return $this;
    }

    /**
     * Get mediumint value.
     *
     * @return int
     */
    public function getMediumint()
    {
        return $this->_getData(self::MEDIUMINT);
    }

    /**
     * Set mediumint value.
     *
     * @param int $mediumint
     *
     * @return $this
     */
    public function setMediumint($mediumint)
    {
        $this->setData(self::MEDIUMINT, $mediumint);

        return $this;
    }

    /**
     * Get int value.
     *
     * @return int
     */
    public function getInt()
    {
        return $this->_getData(self::INT);
    }

    /**
     * Set int value.
     *
     * @param int $int
     *
     * @return $this
     */
    public function setInt($int)
    {
        $this->setData(self::INT, $int);

        return $this;
    }

    /**
     * Get bigint value.
     *
     * @return int
     */
    public function getBigint()
    {
        return $this->_getData(self::BIGINT);
    }

    /**
     * Set bigint value.
     *
     * @param int $bigint
     *
     * @return $this
     */
    public function setBigint($bigint)
    {
        $this->setData(self::BIGINT, $bigint);

        return $this;
    }

    /**
     * Get float value.
     *
     * @return float
     */
    public function getFloat()
    {
        return $this->_getData(self::FLOAT);
    }

    /**
     * Set float value.
     *
     * @param float $float
     *
     * @return $this
     */
    public function setFloat($float)
    {
        $this->setData(self::FLOAT, $float);

        return $this;
    }

    /**
     * Get double value.
     *
     * @return double
     */
    public function getDouble()
    {
        return $this->_getData(self::DOUBLE);
    }

    /**
     * Set double value.
     *
     * @param double $double
     *
     * @return $this
     */
    public function setDouble($double)
    {
        $this->setData(self::DOUBLE, $double);

        return $this;
    }

    /**
     * Get decimal value.
     *
     * @return float
     */
    public function getDecimal()
    {
        return $this->_getData(self::DECIMAL);
    }

    /**
     * Set decimal value.
     *
     * @param float $decimal
     *
     * @return $this
     */
    public function setDecimal($decimal)
    {
        $this->setData(self::DECIMAL, $decimal);

        return $this;
    }

    /**
     * Get datetime value.
     *
     * @return string
     */
    public function getDatetime()
    {
        return $this->_getData(self::DATETIME);
    }

    /**
     * Set datetime value.
     *
     * @param string $datetime
     *
     * @return $this
     */
    public function setDatetime($datetime)
    {
        $this->setData(self::DATETIME, $datetime);

        return $this;
    }

    /**
     * Get timestamp value.
     *
     * @return string
     */
    public function getTimestamp()
    {
        return $this->_getData(self::TIMESTAMP);
    }

    /**
     * Set timestamp value.
     *
     * @param string $timestamp
     *
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->setData(self::TIMESTAMP, $timestamp);

        return $this;
    }

    /**
     * Get time value.
     *
     * @return string
     */
    public function getTime()
    {
        return $this->_getData(self::TIME);
    }

    /**
     * Set time value.
     *
     * @param string $time
     *
     * @return $this
     */
    public function setTime($time)
    {
        $this->setData(self::TIME, $time);

        return $this;
    }

    /**
     * Get year value.
     *
     * @return string
     */
    public function getYear()
    {
        return $this->_getData(self::YEAR);
    }

    /**
     * Set year value.
     *
     * @param string $year
     *
     * @return $this
     */
    public function setYear($year)
    {
        $this->setData(self::YEAR, $year);

        return $this;
    }

    /**
     * Get char value.
     *
     * @return string
     */
    public function getChar()
    {
        return $this->_getData(self::CHAR);
    }

    /**
     * Set char value.
     *
     * @param string $char
     *
     * @return $this
     */
    public function setChar($char)
    {
        $this->setData(self::CHAR, $char);

        return $this;
    }

    /**
     * Get tinyblob value.
     *
     * @return string
     */
    public function getTinyblob()
    {
        return $this->_getData(self::TINYBLOB);
    }

    /**
     * Set tinyblob value.
     *
     * @param string $tinyblob
     *
     * @return $this
     */
    public function setTinyblob($tinyblob)
    {
        $this->setData(self::TINYBLOB, $tinyblob);

        return $this;
    }

    /**
     * Get tinytext value.
     *
     * @return string
     */
    public function getTinytext()
    {
        return $this->_getData(self::TINYTEXT);
    }

    /**
     * Set tinytext value.
     *
     * @param string $tinytext
     *
     * @return $this
     */
    public function setTinytext($tinytext)
    {
        $this->setData(self::TINYTEXT, $tinytext);

        return $this;
    }

    /**
     * Get blob value.
     *
     * @return string
     */
    public function getBlob()
    {
        return $this->_getData(self::BLOB);
    }

    /**
     * Set blob value.
     *
     * @param string $blob
     *
     * @return $this
     */
    public function setBlob($blob)
    {
        $this->setData(self::BLOB, $blob);

        return $this;
    }

    /**
     * Get mediumblob value.
     *
     * @return string
     */
    public function getMediumblob()
    {
        return $this->_getData(self::MEDIUMBLOB);
    }

    /**
     * Set mediumblob value.
     *
     * @param string $mediumblob
     *
     * @return $this
     */
    public function setMediumblob($mediumblob)
    {
        $this->setData(self::MEDIUMBLOB, $mediumblob);

        return $this;
    }

    /**
     * Get mediumtext value.
     *
     * @return string
     */
    public function getMediumtext()
    {
        return $this->_getData(self::MEDIUMTEXT);
    }

    /**
     * Set mediumtext value.
     *
     * @param string $mediumtext
     *
     * @return $this
     */
    public function setMediumtext($mediumtext)
    {
        $this->setData(self::MEDIUMTEXT, $mediumtext);

        return $this;
    }

    /**
     * Get longblob value.
     *
     * @return string
     */
    public function getLongblob()
    {
        return $this->_getData(self::LONGBLOB);
    }

    /**
     * Set longblob value.
     *
     * @param string $longblob
     *
     * @return $this
     */
    public function setLongblob($longblob)
    {
        $this->setData(self::LONGBLOB, $longblob);

        return $this;
    }

    /**
     * Get longtext value.
     *
     * @return string
     */
    public function getLongtext()
    {
        return $this->_getData(self::LONGTEXT);
    }

    /**
     * Set longtext value.
     *
     * @param string $longtext
     *
     * @return $this
     */
    public function setLongtext($longtext)
    {
        $this->setData(self::LONGTEXT, $longtext);

        return $this;
    }

    /**
     * Get enum value.
     *
     * @return string
     */
    public function getEnum()
    {
        return $this->_getData(self::ENUM);
    }

    /**
     * Set enum value.
     *
     * @param string $enum
     *
     * @return $this
     */
    public function setEnum($enum)
    {
        $this->setData(self::ENUM, $enum);

        return $this;
    }

    /**
     * Get set value.
     *
     * @return string
     */
    public function getSet()
    {
        return $this->_getData(self::SET);
    }

    /**
     * Set set value.
     *
     * @param string $set
     *
     * @return $this
     */
    public function setSet($set)
    {
        $this->setData(self::SET, $set);

        return $this;
    }

    /**
     * Get bool value.
     *
     * @return int
     */
    public function getBool()
    {
        return $this->_getData(self::BOOL);
    }

    /**
     * Set bool value.
     *
     * @param int $bool
     *
     * @return $this
     */
    public function setBool($bool)
    {
        $this->setData(self::BOOL, $bool);

        return $this;
    }

    /**
     * Get binary value.
     *
     * @return string
     */
    public function getBinary()
    {
        return $this->_getData(self::BINARY);
    }

    /**
     * Set binary value.
     *
     * @param string $binary
     *
     * @return $this
     */
    public function setBinary($binary)
    {
        $this->setData(self::BINARY, $binary);

        return $this;
    }

    /**
     * Get varbinary value.
     *
     * @return string
     */
    public function getVarbinary()
    {
        return $this->_getData(self::VARBINARY);
    }

    /**
     * Set varbinary value.
     *
     * @param string $varbinary
     *
     * @return $this
     */
    public function setVarbinary($varbinary)
    {
        $this->setData(self::VARBINARY, $varbinary);

        return $this;
    }


}

',
        ],
    ],
];
