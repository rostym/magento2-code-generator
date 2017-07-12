<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Command\Plugin;

/**
 * Class Method
 *
 * @package Krifollk\CodeGenerator\Model\Command\Plugin
 */
class Method
{
    /** @var string */
    private $methodName;

    /** @var bool */
    private $requireBeforeInterceptor;

    /** @var bool */
    private $requireAroundInterceptor;

    /** @var bool */
    private $requireAfterInterceptor;

    /**
     * Method constructor.
     *
     * @param string $methodName
     * @param bool   $requireBeforeInterceptor
     * @param bool   $requireAroundInterceptor
     * @param bool   $requireAfterInterceptor
     */
    public function __construct(
        string $methodName,
        bool $requireBeforeInterceptor,
        bool $requireAroundInterceptor,
        bool $requireAfterInterceptor
    ) {
        $this->methodName = $methodName;
        $this->requireBeforeInterceptor = $requireBeforeInterceptor;
        $this->requireAroundInterceptor = $requireAroundInterceptor;
        $this->requireAfterInterceptor = $requireAfterInterceptor;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function isRequireBeforeInterceptor(): bool
    {
        return $this->requireBeforeInterceptor;
    }

    public function isRequireAroundInterceptor(): bool
    {
        return $this->requireAroundInterceptor;
    }

    public function isRequireAfterInterceptor(): bool
    {
        return $this->requireAfterInterceptor;
    }
}
