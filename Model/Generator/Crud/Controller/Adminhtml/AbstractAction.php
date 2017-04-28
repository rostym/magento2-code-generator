<?php

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml;

use Krifollk\CodeGenerator\Api\GeneratorInterface;
use Zend\Code\Generator\FileGenerator;

/**
 * Class AbstractAction
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
abstract class AbstractAction implements GeneratorInterface
{
    const FILE_PATH_PATTERN = '%s/app/code/%s/Controller/Adminhtml/%s/%s.php';
    const ENTITY_NAME_PATTERN = '\%s\Controller\Adminhtml\%s\%s';
    const NAMESPACE_PATTERN = '\%s\Controller\Adminhtml\%s';

    public function generate(\Krifollk\CodeGenerator\Model\ModuleNameEntity $moduleNameEntity, array $additionalArguments = [])
    {
        $this->checkArguments($additionalArguments);

        return $this->internalGenerate($additionalArguments);
    }

    protected function checkArguments(array $arguments)
    {
        foreach ($this->requiredArguments() as $requiredArgument) {
            if (array_key_exists($requiredArgument, $arguments)) {
                continue;
            }

            throw new \InvalidArgumentException(sprintf('{%s} is required.', $requiredArgument));
        }
    }

    abstract protected function internalGenerate(array $arguments);

    protected function requiredArguments()
    {
        return ['moduleName', 'entityName'];
    }

    protected function wrapToFile($generatorObject)
    {
        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($generatorObject);

        return $fileGenerator;
    }

    protected function generateFilePath($moduleName, $entityName, $actionName)
    {
        return sprintf(self::FILE_PATH_PATTERN, $this->getBasePath(), $moduleName, $entityName, $actionName);
    }

    protected function generateEntityName($moduleName, $entityName, $actionName)
    {
        return sprintf(self::ENTITY_NAME_PATTERN, str_replace('/', '\\', $moduleName), $entityName, $actionName);
    }

    protected function generateNamespace($moduleName, $entityName)
    {
        return sprintf(self::NAMESPACE_PATTERN, str_replace('/', '\\', $moduleName), $entityName);
    }

    private function getBasePath()
    {
        return BP;
    }
}
