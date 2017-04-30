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
 * Class EditActionGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
class EditActionGenerator extends AbstractAction
{
    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return array_merge(parent::requiredArguments(), ['entityRepository']);
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];

        /** @var ClassBuilder $classGenerator */
        $classGenerator = new ClassBuilder($this->generateEntityName($moduleNameEntity, $entityName, 'Edit'));

        /** @var \Magento\Framework\Code\Generator\ClassGenerator $generator */
        $generator = $classGenerator
            ->extendedFrom('\Magento\Backend\App\Action')
            ->usesNamespace($this->generateNamespace($moduleNameEntity, $entityName))

            ->startPropertyBuilding('resultPageFactory')
                ->markAsPrivate()
            ->finishBuilding()

            ->startPropertyBuilding('entityRepository')
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
                    ->type($additionalArguments['entityRepository'])
                ->finishBuilding()

            ->finishBuilding()
            ->startMethodBuilding('execute', $this->getExecuteBody())
                ->markAsPublic()
            ->finishBuilding()
            ->build();

        return new GeneratorResult(
            $this->wrapToFile($generator)->generate(),
            $this->generateFilePath($moduleNameEntity, $entityName, 'Edit'),
            $this->generateEntityName($moduleNameEntity, $entityName, 'Edit')
        );
    }

    private function getConstructorBody(): string
    {
        return '
$this->resultPageFactory = $resultPageFactory;
$this->entityRepository = $entityRepository;
parent::__construct($context);
    ';
    }

    private function getExecuteBody(): string
    {
        $requestFieldName = ListingGenerator::REQUEST_FIELD_NAME;
        return "
\$id = \$this->getRequest()->getParam('$requestFieldName');

return \$this->resultPageFactory->create();

        ";
    }
}
