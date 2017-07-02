<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\TableDescriber\Result;

/**
 * Class Column
 *
 * @package Krifollk\CodeGenerator\Model\TableDescriber\Result
 */
class Column
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var string */
    private $dbType;

    /** @var bool */
    private $isPrimary;

    /** @var bool */
    private $isRequired;

    /**
     * Column constructor.
     *
     * @param string $name
     * @param string $type
     * @param string $dbType
     * @param bool   $isPrimary
     * @param bool   $isRequired
     */
    public function __construct(string $name, string $type, string $dbType, bool $isPrimary, bool $isRequired)
    {
        $this->name = $name;
        $this->type = $type;
        $this->dbType = $dbType;
        $this->isPrimary = $isPrimary;
        $this->isRequired = $isRequired;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function dbType(): string
    {
        return $this->dbType;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }
}
