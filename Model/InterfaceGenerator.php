<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model;

use Magento\Framework\Code\Generator\InterfaceGenerator as MagentoInterfaceGenerator;

/**
 * Class InterfaceGenerator
 *
 * Added constant generation
 *
 * @package Krifollk\CodeGenerator\Model
 */
class InterfaceGenerator extends MagentoInterfaceGenerator
{
    /**
     * @return string
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     */
    public function generate()
    {
        if (!$this->isSourceDirty()) {
            $output = $this->getSourceContent();
            if (!empty($output)) {
                return $output;
            }
        }

        $output = '';

        if (!$this->getName()) {
            return $output;
        }

        $output .= $this->generateDirectives();
        if (null !== ($docBlock = $this->getDocBlock())) {
            $docBlock->setIndentation('');
            $output .= $docBlock->generate();
        }

        $output .= 'interface ' . $this->getName();
        if (!empty($this->extendedClass)) {
            $output .= ' extends ' . $this->extendedClass;
        }

        $output .= self::LINE_FEED
            . '{' . self::LINE_FEED
            . $this->wrapInGroupDoc($this->generateConstants()) . self::LINE_FEED
            . $this->generateMethods()
            . '}' . self::LINE_FEED;

        return $output;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function wrapInGroupDoc($content)
    {
        if ('' === $content) {
            return '';
        }

        $output = '    /**#@+' . self::LINE_FEED;
        $output .= '     * Constants for keys of data array.' . self::LINE_FEED;
        $output .= '     */' . self::LINE_FEED;

        $output .= $content;

        $output .= '    /**#@-*/' . self::LINE_FEED;

        return $output;
    }

    /**
     * Generate constant
     *
     * @return string
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     */
    protected function generateConstants()
    {
        $output = '';
        $constants = $this->getConstants();

        if (!is_array($constants)) {
            return $output;
        }

        foreach ($constants as $constant) {
            $output .= $constant->generate() . self::LINE_FEED;
        }

        return $output;
    }
}
