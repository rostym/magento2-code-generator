<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model;

/**
 * Class ModuleNameEntity
 *
 * @package Krifollk\CodeGenerator\Model
 */
class ModuleNameEntity
{
    /** @var string */
    private $moduleName;

    /**
     * ModuleNameEntity constructor.
     *
     * @param string $moduleName
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $moduleName)
    {
        if (!$this->validate($moduleName)) {
            throw new \InvalidArgumentException(
                sprintf('{%s} Wrong module name. Ex: CompanyCode_ModuleName', $moduleName)
            );
        }
        $this->moduleName = $moduleName;
    }

    public function value(): string
    {
        return $this->moduleName;
    }

    public function asPartOfNamespace(): string
    {
        return str_replace('_', '\\', $this->moduleName);
    }

    public function asPartOfPath(): string
    {
        return str_replace('_', '/', $this->moduleName);
    }

    private function validate(string $moduleName): bool
    {
        return (bool)preg_match('/[A-Z]+[A-Za-z0-9]+_[A-Z]+[A-Z0-9a-z]+/', $moduleName);
    }

    public function __toString()
    {
        return $this->value();
    }
}
