<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad;

use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Magento\Framework\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;

/**
 * Class AbstractModel
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
abstract class AbstractPart extends AbstractGenerator
{
    /**
     * Entity name
     *
     * @var string
     */
    protected $entityName;

    /**
     * AbstractPart constructor.
     *
     * @param string $moduleName
     * @param string $entityName
     */
    public function __construct($moduleName, $entityName)
    {
        parent::__construct($moduleName);
        $this->entityName = $entityName;
    }

    /**
     * @return mixed
     */
    abstract protected function createEntityGenerator();

    /**
     * Wrap
     *
     * @param ClassGenerator $generatorObject
     *
     * @return FileGenerator
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     */
    protected function wrapToFile($generatorObject)
    {
        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($generatorObject);

        return $fileGenerator;
    }

    /**
     * Generate file path
     *
     * @param string $pattern
     *
     * @return string
     */
    protected function generateFilePath($pattern)
    {
        return BP
        . '/app/code/'
        . sprintf($pattern, str_replace('_', '/', $this->moduleName), $this->entityName);
    }

    /**
     * Generate entity name
     *
     * @param string $pattern
     *
     * @return string
     */
    protected function generateEntityName($pattern)
    {
        return sprintf($pattern, $this->normalizeModuleName($this->moduleName), $this->entityName);
    }

    /**
     * Generate package name
     *
     * @param string $pattern
     *
     * @return string
     */
    protected function generatePackageName($pattern)
    {
        return sprintf($pattern, $this->normalizeModuleName($this->moduleName));
    }
}
