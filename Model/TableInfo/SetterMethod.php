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
 * Class SetterMethod
 *
 * @package Krifollk\CodeGenerator\Model\TableInfo
 */
class SetterMethod extends AbstractMethod
{
    /**
     * Pattern
     */
    const BODY_METHOD_PATTERN = '$this->setData(self::%s, $%s);' . "\n\n" . 'return $this;';

    /**
     * Parameter name
     *
     * @var string
     */
    private $parameterName = '';

    /**
     * SetterMethod constructor.
     *
     * @param string            $constName
     * @param string            $name
     * @param string            $setterParameterName
     * @param DocBlockGenerator $docBlock
     */
    public function __construct($constName, $name, $setterParameterName, DocBlockGenerator $docBlock)
    {
        parent::__construct($constName, $docBlock);
        $this->parameterName = $setterParameterName;
        $this->name = 'set' . $name;
    }

    /**
     * Get method body content
     *
     * @return string
     */
    public function getBody()
    {
        return sprintf(self::BODY_METHOD_PATTERN, $this->constName, $this->getParameterName());
    }

    /**
     * Get method parameter name
     *
     * @return string
     */
    public function getParameterName()
    {
        return $this->parameterName;
    }
}
