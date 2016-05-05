<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator;

use Krifollk\CodeGenerator\Api\GeneratorInterface;

/**
 * Class AbstractGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    /**
     * Directory separator
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * AbstractGenerator constructor.
     *
     * @param $moduleName
     */
    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @param string $moduleName
     *
     * @return string
     */
    protected function normalizeModuleName($moduleName)
    {
        return str_replace('/', '\\', $moduleName);
    }
}
