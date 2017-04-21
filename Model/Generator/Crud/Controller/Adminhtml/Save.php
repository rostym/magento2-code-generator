<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;

/**
 * Class Save
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
class Save extends AbstractAction
{
    public function generate(array $arguments = [])
    {
        $this->checkArguments($arguments);

        return $this->internalGenerate($arguments);
    }

    protected function internalGenerate(array $arguments)
    {
        /** @var ClassBuilder $classGenerator */
        $classGenerator = new ClassBuilder($this->generateEntityName($arguments['moduleName'], $arguments['entityName'], 'Save'));
        $dataPersistorKey = str_replace('/', '_', mb_strtolower($arguments['moduleName'])) .'_'. mb_strtolower($arguments['entityName']);
        /** @var \Magento\Framework\Code\Generator\ClassGenerator $generator */
        $generator = $classGenerator
            ->extendedFrom('\Magento\Backend\App\Action')
            ->usesNamespace($this->generateNamespace($arguments['moduleName'], $arguments['entityName']))

            ->startPropertyBuilding('resultPageFactory')
                ->markAsPrivate()
            ->finishBuilding()

            ->startPropertyBuilding('dataPersistor')
                ->markAsPrivate()
            ->finishBuilding()

            ->startPropertyBuilding('resultRedirectFactory')
                ->markAsPrivate()
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
                    ->type($arguments['entityRepository'])
                ->finishBuilding()

                ->startArgumentBuilding('entityFactory')
                    ->type($arguments['entityFactory'])
                ->finishBuilding()

            ->finishBuilding()

            ->startMethodBuilding('execute', $this->getExecuteBody($arguments['idFieldName'], $dataPersistorKey))
                ->markAsPublic()
            ->finishBuilding()

            ->build();

        return new GeneratorResult(
            $this->wrapToFile($generator)->generate(),
            $this->generateFilePath($arguments['moduleName'], $arguments['entityName'], 'Save'),
            $this->generateEntityName($arguments['moduleName'], $arguments['entityName'], 'Save')
        );
    }

    protected function requiredArguments()
    {
        return array_merge(parent::requiredArguments(), ['idFieldName', 'entityRepository', 'entityFactory']);
    }

    private function getConstructorBody()
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

    private function getExecuteBody($idFieldName, $dataPersistorKey)
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
