<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
return [
    [
        [ // input data
            'moduleName'    => 'Test/Module',
            'entityName'    => 'Test',
            'tableName'     => 'all_data_types'
        ],
        [// generated data
            'destinationFile' => 'app/code/Test/Module/Api/Data/TestInterface.php',
            'entityName'      => '\Test\Module\Api\Data\TestInterface',
            'content'         => '<?php

namespace Test\Module\Api\Data;

/**
 * Interface Test
 *
 * @package Test\Module\Api\Data
 */
interface TestInterface
{
    /**#@+
     * Constants for keys of data array.
     */
    const ID = \'id\';
    const VARCHAR = \'varchar\';
    const TINYINT = \'tinyint\';
    const TEXT = \'text\';
    const DATE = \'date\';
    const SMALLINT = \'smallint\';
    const MEDIUMINT = \'mediumint\';
    const INT = \'int\';
    const BIGINT = \'bigint\';
    const FLOAT = \'float\';
    const DOUBLE = \'double\';
    const DECIMAL = \'decimal\';
    const DATETIME = \'datetime\';
    const TIMESTAMP = \'timestamp\';
    const TIME = \'time\';
    const YEAR = \'year\';
    const CHAR = \'char\';
    const TINYBLOB = \'tinyblob\';
    const TINYTEXT = \'tinytext\';
    const BLOB = \'blob\';
    const MEDIUMBLOB = \'mediumblob\';
    const MEDIUMTEXT = \'mediumtext\';
    const LONGBLOB = \'longblob\';
    const LONGTEXT = \'longtext\';
    const ENUM = \'enum\';
    const SET = \'set\';
    const BOOL = \'bool\';
    const BINARY = \'binary\';
    const VARBINARY = \'varbinary\';
    /**#@-*/

    /**
     * Get id value.
     *
     * @return int
     */
    public function getId();

    /**
     * Set id value.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Get varchar value.
     *
     * @return string
     */
    public function getVarchar();

    /**
     * Set varchar value.
     *
     * @param string $varchar
     *
     * @return $this
     */
    public function setVarchar($varchar);

    /**
     * Get tinyint value.
     *
     * @return int
     */
    public function getTinyint();

    /**
     * Set tinyint value.
     *
     * @param int $tinyint
     *
     * @return $this
     */
    public function setTinyint($tinyint);

    /**
     * Get text value.
     *
     * @return string
     */
    public function getText();

    /**
     * Set text value.
     *
     * @param string $text
     *
     * @return $this
     */
    public function setText($text);

    /**
     * Get date value.
     *
     * @return string
     */
    public function getDate();

    /**
     * Set date value.
     *
     * @param string $date
     *
     * @return $this
     */
    public function setDate($date);

    /**
     * Get smallint value.
     *
     * @return int
     */
    public function getSmallint();

    /**
     * Set smallint value.
     *
     * @param int $smallint
     *
     * @return $this
     */
    public function setSmallint($smallint);

    /**
     * Get mediumint value.
     *
     * @return int
     */
    public function getMediumint();

    /**
     * Set mediumint value.
     *
     * @param int $mediumint
     *
     * @return $this
     */
    public function setMediumint($mediumint);

    /**
     * Get int value.
     *
     * @return int
     */
    public function getInt();

    /**
     * Set int value.
     *
     * @param int $int
     *
     * @return $this
     */
    public function setInt($int);

    /**
     * Get bigint value.
     *
     * @return int
     */
    public function getBigint();

    /**
     * Set bigint value.
     *
     * @param int $bigint
     *
     * @return $this
     */
    public function setBigint($bigint);

    /**
     * Get float value.
     *
     * @return float
     */
    public function getFloat();

    /**
     * Set float value.
     *
     * @param float $float
     *
     * @return $this
     */
    public function setFloat($float);

    /**
     * Get double value.
     *
     * @return double
     */
    public function getDouble();

    /**
     * Set double value.
     *
     * @param double $double
     *
     * @return $this
     */
    public function setDouble($double);

    /**
     * Get decimal value.
     *
     * @return float
     */
    public function getDecimal();

    /**
     * Set decimal value.
     *
     * @param float $decimal
     *
     * @return $this
     */
    public function setDecimal($decimal);

    /**
     * Get datetime value.
     *
     * @return string
     */
    public function getDatetime();

    /**
     * Set datetime value.
     *
     * @param string $datetime
     *
     * @return $this
     */
    public function setDatetime($datetime);

    /**
     * Get timestamp value.
     *
     * @return string
     */
    public function getTimestamp();

    /**
     * Set timestamp value.
     *
     * @param string $timestamp
     *
     * @return $this
     */
    public function setTimestamp($timestamp);

    /**
     * Get time value.
     *
     * @return string
     */
    public function getTime();

    /**
     * Set time value.
     *
     * @param string $time
     *
     * @return $this
     */
    public function setTime($time);

    /**
     * Get year value.
     *
     * @return string
     */
    public function getYear();

    /**
     * Set year value.
     *
     * @param string $year
     *
     * @return $this
     */
    public function setYear($year);

    /**
     * Get char value.
     *
     * @return string
     */
    public function getChar();

    /**
     * Set char value.
     *
     * @param string $char
     *
     * @return $this
     */
    public function setChar($char);

    /**
     * Get tinyblob value.
     *
     * @return string
     */
    public function getTinyblob();

    /**
     * Set tinyblob value.
     *
     * @param string $tinyblob
     *
     * @return $this
     */
    public function setTinyblob($tinyblob);

    /**
     * Get tinytext value.
     *
     * @return string
     */
    public function getTinytext();

    /**
     * Set tinytext value.
     *
     * @param string $tinytext
     *
     * @return $this
     */
    public function setTinytext($tinytext);

    /**
     * Get blob value.
     *
     * @return string
     */
    public function getBlob();

    /**
     * Set blob value.
     *
     * @param string $blob
     *
     * @return $this
     */
    public function setBlob($blob);

    /**
     * Get mediumblob value.
     *
     * @return string
     */
    public function getMediumblob();

    /**
     * Set mediumblob value.
     *
     * @param string $mediumblob
     *
     * @return $this
     */
    public function setMediumblob($mediumblob);

    /**
     * Get mediumtext value.
     *
     * @return string
     */
    public function getMediumtext();

    /**
     * Set mediumtext value.
     *
     * @param string $mediumtext
     *
     * @return $this
     */
    public function setMediumtext($mediumtext);

    /**
     * Get longblob value.
     *
     * @return string
     */
    public function getLongblob();

    /**
     * Set longblob value.
     *
     * @param string $longblob
     *
     * @return $this
     */
    public function setLongblob($longblob);

    /**
     * Get longtext value.
     *
     * @return string
     */
    public function getLongtext();

    /**
     * Set longtext value.
     *
     * @param string $longtext
     *
     * @return $this
     */
    public function setLongtext($longtext);

    /**
     * Get enum value.
     *
     * @return string
     */
    public function getEnum();

    /**
     * Set enum value.
     *
     * @param string $enum
     *
     * @return $this
     */
    public function setEnum($enum);

    /**
     * Get set value.
     *
     * @return string
     */
    public function getSet();

    /**
     * Set set value.
     *
     * @param string $set
     *
     * @return $this
     */
    public function setSet($set);

    /**
     * Get bool value.
     *
     * @return int
     */
    public function getBool();

    /**
     * Set bool value.
     *
     * @param int $bool
     *
     * @return $this
     */
    public function setBool($bool);

    /**
     * Get binary value.
     *
     * @return string
     */
    public function getBinary();

    /**
     * Set binary value.
     *
     * @param string $binary
     *
     * @return $this
     */
    public function setBinary($binary);

    /**
     * Get varbinary value.
     *
     * @return string
     */
    public function getVarbinary();

    /**
     * Set varbinary value.
     *
     * @param string $varbinary
     *
     * @return $this
     */
    public function setVarbinary($varbinary);

}

',
        ],
    ],
];
