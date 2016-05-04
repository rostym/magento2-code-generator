<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Triad\Repository;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\InterfaceGenerator;
use Magento\Framework\Code\Generator\InterfaceMethodGenerator;

/**
 * Class RepositoryInterfacePart
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Triad
 */
class RepositoryInterfacePart extends AbstractRepositoryPart
{
    const REPOSITORY_INTERFACE_NAME_PATTERN      = '\%s\Api\%sRepositoryInterface';
    const REPOSITORY_INTERFACE_FILE_NAME_PATTERN = '%s/Api/%sRepositoryInterface.php';

    /**
     * Generate entity
     *
     * @return GeneratorResultInterface
     * @throws \Zend\Code\Generator\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function generate()
    {
        $entityGenerator = $this->createEntityGenerator();
        $entityGenerator->setName($this->generateEntityName(self::REPOSITORY_INTERFACE_NAME_PATTERN));
        $entityGenerator->addUse(self::SEARCH_CRITERIA_INTERFACE_NAME);

        $entityGenerator->addMethodFromGenerator($this->createSaveMethod());
        $entityGenerator->addMethodFromGenerator($this->createGetByIdMethod());
        $entityGenerator->addMethodFromGenerator($this->createGetListMethod());
        $entityGenerator->addMethodFromGenerator($this->createDeleteMethod());
        $entityGenerator->addMethodFromGenerator($this->createDeleteByIdMethod());

        return new GeneratorResult(
            $this->wrapToFile($entityGenerator)->generate(),
            $this->generateFilePath(self::REPOSITORY_INTERFACE_FILE_NAME_PATTERN),
            $this->generateEntityName(self::REPOSITORY_INTERFACE_NAME_PATTERN)
        );
    }

    /**
     * @return InterfaceGenerator
     */
    protected function createEntityGenerator()
    {
        return new InterfaceGenerator();
    }

    /**
     * @return InterfaceMethodGenerator
     */
    public function createMethodGenerator()
    {
        return new InterfaceMethodGenerator();
    }
}
