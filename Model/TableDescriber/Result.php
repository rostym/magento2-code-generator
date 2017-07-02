<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\TableDescriber;

use Krifollk\CodeGenerator\Model\TableDescriber\Result\Column;

/**
 * Class Result
 *
 * @package Krifollk\CodeGenerator\Model\TableDescriber
 */
class Result
{
    /** @var Column|null */
    private $primaryColumn;

    /** @var Column[] */
    private $columns;

    /** @var string */
    private $tableName;

    /**
     * Result constructor.
     *
     * @param string   $tableName
     * @param Column   $primaryColumn
     * @param Column[] $columns
     */
    public function __construct(string $tableName, Column $primaryColumn = null, Column ...$columns)
    {
        $this->primaryColumn = $primaryColumn;
        $this->columns = $columns;
        $this->tableName = $tableName;
    }

    /**
     * @throws \RuntimeException
     */
    public function primaryColumn(): Column
    {
        if ($this->primaryColumn === null) {
            throw new \RuntimeException('Primary column not found.');
        }

        return $this->primaryColumn;
    }

    /**
     * @return Column[]
     */
    public function columns(): array
    {
        return $this->columns;
    }

    public function tableName(): string
    {
        return $this->tableName;
    }
}
