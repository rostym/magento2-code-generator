<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Module;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Zend\Code\Generator\FileGenerator;

/**
 * Class Registration
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Module
 */
class Registration extends AbstractGenerator
{
    const FILE                    = BP . '/app/code/%s/registration.php';
    const COMPONENT_REGISTRAR_USE = 'Magento\Framework\Component\ComponentRegistrar';
    const BODY_PATTERN            = 'ComponentRegistrar::register(ComponentRegistrar::MODULE, \'%s\', __DIR__);';

    /**
     * Generate entity
     *
     * @return GeneratorResultInterface
     */
    public function generate()
    {
        $fileGenerator = new FileGenerator();
        $fileGenerator->setUse(self::COMPONENT_REGISTRAR_USE);
        $fileGenerator->setBody($this->generateBody());

        //todo move to abstract class (createResult method)
        return new GeneratorResult(
            $fileGenerator->generate(),
            $this->generateFilePath(),
            null
        );
    }

    /**
     * @return string
     */
    protected function generateBody()
    {
        return sprintf(self::BODY_PATTERN, str_replace('/', '_', $this->moduleName));
    }

    /**
     * @return string
     */
    protected function generateFilePath()
    {
        return sprintf(self::FILE, $this->moduleName);
    }
}
