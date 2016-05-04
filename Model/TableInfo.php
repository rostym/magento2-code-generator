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
    /**
     * DB -> PHP map of types
     *
     * @var array
     */
    protected $mapTypes = [
        'varchar'   => 'string',
        'smallint'  => 'int',
        'text'      => 'string',
        'decimal'   => 'float',
        'timestamp' => 'string',
        'datetime'  => 'string',
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
            $baseMethodName = $this->convertColumnName($columnName);

            if ($data['PRIMARY'] === true) {
                $this->idFieldName = $columnName;
            }

            $columnType = $this->detectType($data['DATA_TYPE']);

            $this->addSetter($baseMethodName, $columnType, $constName);
            $this->addGetter($baseMethodName, $columnType, $constName);
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
        return isset($this->mapTypes[$dbType]) ? $this->mapTypes[$dbType] : $dbType;
    }

    /**
     * @param string $baseMethodName
     * @param string $columnType
     * @param string $constName
     *
     * @return DocBlockGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function addSetter($baseMethodName, $columnType, $constName)
    {
        $docBLock = new DocBlockGenerator();
        $docBLock->setShortDescription('Set ' . lcfirst($baseMethodName) . ' value.');

        $docBLock->setTag((new GenericTag('param', $columnType . ' $' . lcfirst($baseMethodName))));
        $docBLock->setTag((new GenericTag()));
        $docBLock->setTag((new GenericTag('return', '$this')));

        $this->setters[] = new SetterMethod($constName, $baseMethodName, lcfirst($baseMethodName), $docBLock);

        return $this;
    }

    /**
     * @param string $baseMethodName
     * @param string $columnType
     * @param string $constName
     *
     * @return $this
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function addGetter($baseMethodName, $columnType, $constName)
    {
        $docBLock = new DocBlockGenerator();
        $docBLock->setShortDescription('Get ' . lcfirst($baseMethodName) . ' value.');
        $docBLock->setTag((new GenericTag('return', $columnType)));

        $this->getters[] = new GetterMethod($constName, $baseMethodName, $docBLock);

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
