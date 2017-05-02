<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder;
use Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\ListingGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class DeleteActionGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
class DeleteActionGenerator extends AbstractAction
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return array_merge(parent::requiredArguments(), ['entityRepositoryInterface']);
    }

    /**
     * @inheritdoc
     * @throws \InvalidArgumentException
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        $entityRepositoryInterface = $additionalArguments['entityRepositoryInterface'];
        $classBuilder = new ClassBuilder($this->generateEntityName($moduleNameEntity, $entityName, 'Delete'));

        $classBuilder
            ->extendedFrom('\Magento\Backend\App\Action')
            ->usesNamespace($this->generateNamespace($moduleNameEntity, $entityName))
            ->startPropertyBuilding('entityRepository')
                ->markAsPrivate()
            ->finishBuilding()
            ->startMethodBuilding('__construct', $this->getConstructorBody())
                ->markAsPublic()
                ->startArgumentBuilding('context')
                    ->type('\Magento\Backend\App\Action\Context')
                ->finishBuilding()
                ->startArgumentBuilding('entityRepository')
                    ->type($entityRepositoryInterface)
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('execute', $this->getExecuteBody(ListingGenerator::REQUEST_FIELD_NAME))
                ->markAsPublic()
            ->finishBuilding();

        return new GeneratorResult(
            $this->wrapToFile($classBuilder->build())->generate(),
            $this->generateFilePath($moduleNameEntity, $entityName, 'Delete'),
            $this->generateEntityName($moduleNameEntity, $entityName, 'Delete')
        );
    }

    private function getConstructorBody(): string
    {
        return '
$this->entityRepository = $entityRepository;
parent::__construct($context);
    ';
    }

    private function getExecuteBody(string $requestIdFiledName): string
    {
        return "
/** @var \Magento\Backend\Model\View\Result\Redirect \$resultRedirect */
\$resultRedirect = \$this->resultRedirectFactory->create();
\$id = \$this->getRequest()->getParam('$requestIdFiledName');
if (\$id === null) {
    \$this->messageManager->addErrorMessage(__('We can\'t find a block to delete .'));
    return \$resultRedirect->setPath('*/*/');
}
try {
    \$this->entityRepository->deleteById(\$id);
    \$this->messageManager->addSuccessMessage(__('Entity has been deleted.'));

    return \$resultRedirect->setPath('*/*/');
} catch (\Exception \$e) {
    \$this->messageManager->addErrorMessage(\$e->getMessage());
    
    return \$resultRedirect->setPath('*/*/edit', ['$requestIdFiledName' => \$id]);
}
";
    }
}
