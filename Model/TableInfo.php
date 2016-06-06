<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model;

use Krifollk\CodeGenerator\Model\TableInfo\Constant;
use Krifollk\CodeGenerator\Model\TableInfo\GetterMethod;
use Krifollk\CodeGenerator\Model\TableInfo\SetterMethod;
use Magento\Framework\App\ResourceConnection;
use Zend\Code\Generator\DocBlockGenerator;

/**
 * Class Table
 *
 * @package Krifollk\CodeGenerator\Model
 */
class TableInfo
{
    /**#@+
     * Doc block patterns
     */
    const GETTER_DOC_PATTERN = 'Get %s value.';
    const SETTER_DOC_PATTERN = 'Set %s value.';
    /**#@-*/

    /**
     * DB -> PHP map of types
     *
     * @var array
     */
    protected $mapTypes = [
        'varchar'    => 'string',
        'smallint'   => 'int',
        'tinyint'    => 'int',
        'mediumint'  => 'int',
        'bigint'     => 'int',
        'text'       => 'string',
        'decimal'    => 'float',
        'timestamp'  => 'string',
        'datetime'   => 'string',
        'date'       => 'string',
        'time'       => 'string',
        'year'       => 'string',
        'char'       => 'string',
        'tinyblob'   => 'string',
        'tinytext'   => 'string',
        'blob'       => 'string',
        'mediumblob' => 'string',
        'mediumtext' => 'string',
        'longblob'   => 'string',
        'longtext'   => 'string',
        'enum'       => 'string',
        'set'        => 'string',
        'binary'     => 'string',
        'varbinary'  => 'string',
    ];

    /**
     * Connection
     *
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * Getters storage
     *
     * @var \Krifollk\CodeGenerator\Model\TableInfo\GetterMethod[]
     */
    private $getters = [];

    /**
     * Setters storage
     *
     * @var \Krifollk\CodeGenerator\Model\TableInfo\SetterMethod[]
     */
    private $setters = [];

    /**
     * Constant storage
     *
     * @var \Krifollk\CodeGenerator\Model\TableInfo\Constant[]
     */
    private $constant = [];

    /**
     * Items count
     *
     * @var int
     */
    private $itemsCount = 0;

    /**
     * Primary column name
     *
     * @var string
     */
    private $idFieldName = '';

    /**
     * Table constructor.
     *
     * @param string                                    $tableName
     * @param \Magento\Framework\App\ResourceConnection $connection
     *
     * @throws \DomainException
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    public function __construct($tableName, ResourceConnection $connection)
    {
        $this->resourceConnection = $connection;
        $this->init($tableName);
    }

    /**
     * Describe table
     *
     * @param string $tableName
     *
     * @return $this
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \DomainException
     */
    protected function init($tableName)
    {
        foreach ($this->getColumnsInfo($tableName) as $columnName => $data) {
            $constName = mb_strtoupper($columnName);
            $methodName = $this->convertColumnName($columnName);

            if ($data['PRIMARY'] === true) {
                $this->idFieldName = $columnName;
            }

            $columnType = $this->detectType($data['DATA_TYPE']);

            $this->addSetter($methodName, $columnType, $constName);
            $this->addGetter($methodName, $columnType, $constName);
            $this->addConstant($constName, $columnName);

            $this->itemsCount++;
        }

        return $this;
    }

    /**
     * Get columns info
     *
     * @param string $tableName
     *
     * @return array
     * @throws \DomainException
     */
    protected function getColumnsInfo($tableName)
    {
        return $this->resourceConnection->getConnection()->describeTable($tableName);
    }

    /**
     * Convert column name
     *
     * @param string $columnName
     *
     * @return string
     */
    protected function convertColumnName($columnName)
    {
        return str_replace('_', '', ucwords($columnName, '_'));
    }

    /**
     * Detect type
     *
     * @param string $dbType
     *
     * @return string
     */
    protected function detectType($dbType)
    {
        $type = explode('(', $dbType)[0];//retrieving type without length

        return isset($this->mapTypes[$type]) ? $this->mapTypes[$type] : $type;
    }

    /**
     * @param string $methodName
     * @param string $columnType
     * @param string $constName
     *
     * @return DocBlockGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function addSetter($methodName, $columnType, $constName)
    {
        //todo move doc block generation to  Krifollk\CodeGenerator\Model\Generator\Triad\Model
        $docBLock = new DocBlockGenerator();

        $parameterName = lcfirst($methodName);
        $docBLock->setShortDescription(sprintf(self::SETTER_DOC_PATTERN, $parameterName));
        $docBLock->setTag((new GenericTag('param', sprintf('%s $%s', $columnType, $parameterName))));
        $docBLock->setTag((new GenericTag()));
        $docBLock->setTag((new GenericTag('return', '$this')));

        $this->setters[] = new SetterMethod($constName, $methodName, $parameterName, $docBLock);

        return $this;
    }

    /**
     * @param string $methodName
     * @param string $columnType
     * @param string $constName
     *
     * @return $this
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function addGetter($methodName, $columnType, $constName)
    {
        //todo move doc block generation to  Krifollk\CodeGenerator\Model\Generator\Triad\Model
        $docBLock = new DocBlockGenerator();
        $parameterName = lcfirst($methodName);
        $docBLock->setShortDescription(sprintf(self::GETTER_DOC_PATTERN, $parameterName));
        $docBLock->setTag((new GenericTag('return', $columnType)));

        $this->getters[] = new GetterMethod($constName, $methodName, $docBLock);

        return $this;
    }

    /**
     * Add constant
     *
     * @param string $columnName
     * @param string $constName
     *
     * @return $this
     */
    protected function addConstant($constName, $columnName)
    {
        $this->constant[] = new Constant($constName, $columnName);

        return $this;
    }

    /**
     * @return GetterMethod[]
     */
    public function getGetters()
    {
        return $this->getters;
    }

    /**
     * @return TableInfo\SetterMethod[]
     */
    public function getSetters()
    {
        return $this->setters;
    }

    /**
     * @return TableInfo\Constant[]
     */
    public function getConstants()
    {
        return $this->constant;
    }

    /**
     * @return int
     */
    public function getItemsCount()
    {
        return $this->itemsCount;
    }

    /**
     * @return string
     */
    public function getIdFieldName()
    {
        return $this->idFieldName;
    }
}
