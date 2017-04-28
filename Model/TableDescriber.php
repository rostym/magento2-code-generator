<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model;

use Krifollk\CodeGenerator\Model\TableDescriber\Result;

/**
 * Class TableDescriber
 *
 * @package Krifollk\CodeGenerator\Model
 */
class TableDescriber
{
    /** @var \Magento\Framework\App\ResourceConnection */
    private $connection;

    /**
     * DB -> PHP map of types
     *
     * @var array
     */
    public static $typeMap = [
        'smallint'  => 'int',
        'tinyint'   => 'int',
        'mediumint' => 'int',
        'bigint'    => 'int',
        'decimal'   => 'float',
    ];

    /**
     * TableDescriber constructor.
     *
     * @param \Magento\Framework\App\ResourceConnection $connection
     */
    public function __construct(\Magento\Framework\App\ResourceConnection $connection)
    {
        $this->connection = $connection;
    }

    public function describe(string $tableName): Result
    {
        $columnsData = $this->connection->getConnection()->describeTable($tableName);
        $columnObjects = [];
        $primaryColumn = null;
        foreach ($columnsData as $columnName => $data) {
            $dbType = $this->extractDbType($data);
            $type = $this->getPhpType($dbType);

            $columnObject = new Result\Column($columnName, $type, $dbType, $this->isPrimary($data));
            $columnObjects[] = $columnObject;

            if ($columnObject->isPrimary()) {
                $primaryColumn = $columnObject;
            }
        }

        return new Result($tableName, $primaryColumn, ...$columnObjects);
    }

    private function isPrimary(array $data): bool
    {
        return $data['PRIMARY'] === true;
    }

    private function extractDbType(array $data): string
    {
        return explode('(', $data['DATA_TYPE'])[0];
    }

    private function getPhpType(string $dbType): string
    {
        return self::$typeMap[$dbType] ?? 'string';
    }
}
