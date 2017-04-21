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
 * Class Edit
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
class Edit extends AbstractAction
{
    protected function internalGenerate(array $arguments)
    {
        /** @var ClassBuilder $classGenerator */
        $classGenerator = new ClassBuilder($this->generateEntityName($arguments['moduleName'], $arguments['entityName'], 'Edit'));

        /** @var \Magento\Framework\Code\Generator\ClassGenerator $generator */
        $generator = $classGenerator
            ->extendedFrom('\Magento\Backend\App\Action')
            ->usesNamespace($this->generateNamespace($arguments['moduleName'], $arguments['entityName']))

            ->startPropertyBuilding('resultPageFactory')
                ->markAsPrivate()
            ->finishBuilding()

            ->startPropertyBuilding('entityRepository')
                ->markAsPrivate()
            ->finishBuilding()

            ->startPropertyBuilding('resultRedirectFactory')
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

                ->startArgumentBuilding('entityRepository')
                    ->type($arguments['entityRepository'])
                ->finishBuilding()

            ->finishBuilding()
            ->startMethodBuilding('execute', $this->getExecuteBody())
                ->markAsPublic()
            ->finishBuilding()
            ->build();

        return new GeneratorResult(
            $this->wrapToFile($generator)->generate(),
            $this->generateFilePath($arguments['moduleName'], $arguments['entityName'], 'Edit'),
            $this->generateEntityName($arguments['moduleName'], $arguments['entityName'], 'Edit')
        );
    }

    private function getConstructorBody()
    {
        return '
$this->resultPageFactory = $resultPageFactory;
$this->entityRepository = $entityRepository;
$this->resultRedirectFactory = $context->getResultRedirectFactory();
parent::__construct($context);
    ';
    }

    private function getExecuteBody()
    {
        return "
\$id = \$this->getRequest()->getParam('id');

return \$this->resultPageFactory->create();

        ";
    }
}
