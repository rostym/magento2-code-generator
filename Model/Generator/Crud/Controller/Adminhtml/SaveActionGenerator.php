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
 * Class SaveActionGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
class SaveActionGenerator extends AbstractAction
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return array_merge(parent::requiredArguments(), ['entityRepository', 'entity', 'dataPersistorEntityKey']);
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        $dataPersistorEntityKey = $additionalArguments['dataPersistorEntityKey'];
        $entityRepository = $additionalArguments['entityRepository'];
        $requestFieldName = ListingGenerator::REQUEST_FIELD_NAME;
        $entityFactory = sprintf('%sFactory', $additionalArguments['entity']);

        $classGenerator = new ClassBuilder($this->generateEntityName($moduleNameEntity, $entityName, 'Save'));

        /** @var \Magento\Framework\Code\Generator\ClassGenerator $generator */
        $generator = $classGenerator
            ->extendedFrom('\Magento\Backend\App\Action')
            ->usesNamespace($this->generateNamespace($moduleNameEntity, $entityName))

            ->startPropertyBuilding('resultPageFactory')
                ->markAsPrivate()
            ->finishBuilding()

            ->startPropertyBuilding('dataPersistor')
                ->markAsPrivate()
            ->finishBuilding()

            ->startPropertyBuilding('resultRedirectFactory')
                ->markAsProtected()
            ->finishBuilding()

            ->startPropertyBuilding('entityRepository')
                ->markAsPrivate()
            ->finishBuilding()

            ->startPropertyBuilding('entityFactory')
                ->markAsPrivate()
            ->finishBuilding()

            ->startMethodBuilding('__construct', $this->getConstructorBody())
                ->markAsPublic()

                ->startArgumentBuilding('context')
                    ->type('\Magento\Backend\App\Action\Context')
                ->finishBuilding()

                ->startArgumentBuilding('resultPageFactory')
                    ->type('\Magento\Framework\View\Result\PageFactory')
                ->finishBuilding()

                ->startArgumentBuilding('dataPersistor')
                    ->type('\Magento\Framework\App\Request\DataPersistorInterface')
                ->finishBuilding()

                ->startArgumentBuilding('entityRepository')
                    ->type($entityRepository)
                ->finishBuilding()

                ->startArgumentBuilding('entityFactory')
                    ->type($entityFactory)
                ->finishBuilding()

            ->finishBuilding()

            ->startMethodBuilding('execute', $this->getExecuteBody($requestFieldName, $dataPersistorEntityKey))
                ->markAsPublic()
            ->finishBuilding()

            ->build();

        return new GeneratorResult(
            $this->wrapToFile($generator)->generate(),
            $this->generateFilePath($moduleNameEntity, $entityName, 'Save'),
            $this->generateEntityName($moduleNameEntity, $entityName, 'Save')
        );
    }

    private function getConstructorBody(): string
    {
        return <<<'EOT'
$this->resultPageFactory = $resultPageFactory;
$this->dataPersistor = $dataPersistor;
$this->entityRepository = $entityRepository;
$this->entityFactory = $entityFactory;
$this->resultRedirectFactory = $context->getResultRedirectFactory();
parent::__construct($context);
EOT;
    }

    private function getExecuteBody($idFieldName, $dataPersistorKey): string
    {
        return <<<EOT
/** @var \Magento\Backend\Model\View\Result\Redirect \$resultRedirect */
\$resultRedirect = \$this->resultRedirectFactory->create();
\$data = \$this->getRequest()->getPostValue();

if (!\$data) {
    return \$resultRedirect->setPath('*/*/');
}

\$id = \$this->getRequest()->getParam('$idFieldName');

try {
    if (\$id === null) {
        \$entity = \$this->entityFactory->create();
    } else {
        \$entity = \$this->entityRepository->getById(\$id);
    }
    
    \$entity->setData(\$data);
    \$this->entityRepository->save(\$entity);
    \$this->messageManager->addSuccessMessage(__('You saved the entity.'));
    \$this->dataPersistor->clear('$dataPersistorKey');
    
    if (\$this->getRequest()->getParam('back')) {
        return \$resultRedirect->setPath('*/*/edit', ['id' => \$entity->getId()]);
    }
    
    return \$resultRedirect->setPath('*/*/');
    
} catch (\Magento\Framework\Exception\LocalizedException \$e) {
    \$this->messageManager->addErrorMessage(\$e->getMessage());
} catch (\Exception \$e) {
    \$this->messageManager->addExceptionMessage(\$e, __('Something went wrong while saving the entity.'));
}

\$this->dataPersistor->set('$dataPersistorKey', \$data);
return \$resultRedirect->setPath('*/*/edit', ['id' => \$this->getRequest()->getParam('id')]);

EOT;
    }
}
