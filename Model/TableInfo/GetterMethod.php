<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\TableInfo;

use Zend\Code\Generator\DocBlockGenerator;

/**
 * Class GetterMethod
 *
 * @package Krifollk\CodeGenerator\Model\TableInfo
 */
class GetterMethod extends AbstractMethod
{
    /**
     * Pattern
     */
    const BODY_METHOD_PATTERN = 'return $this->_getData(self::%s);';

    /**
     * GetterMethod constructor.
     *
     * @param string            $constName
     * @param string            $name
     * @param DocBlockGenerator $docBlock
     */
    public function __construct($constName, $name, DocBlockGenerator $docBlock)
    {
        parent::__construct($constName, $docBlock);
        $this->name = 'get' . $name;
    }

    /**
     * Get method body content
     *
     * @return string
     */
    public function getBody()
    {
        return sprintf(self::BODY_METHOD_PATTERN, $this->constName);
    }
}
