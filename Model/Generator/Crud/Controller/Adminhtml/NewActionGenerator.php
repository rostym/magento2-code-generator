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
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class NewActionGenerator
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\Controller\Adminhtml
 */
class NewActionGenerator extends AbstractAction
{
    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];

        $classGenerator = new ClassBuilder(
            $this->generateEntityName($moduleNameEntity, $entityName, 'NewAction')
        );

        /** @var \Magento\Framework\Code\Generator\ClassGenerator $generator */
        $generator = $classGenerator
            ->extendedFrom('\Magento\Backend\App\Action')
            ->usesNamespace($this->generateNamespace($moduleNameEntity, $entityName))
            ->startPropertyBuilding('resultForwardFactory')
                ->markAsPrivate()
            ->finishBuilding()
            ->startMethodBuilding('__construct', $this->getConstructorBody())
                ->markAsPublic()
                ->startArgumentBuilding('context')
                    ->type('\Magento\Backend\App\Action\Context')
                ->finishBuilding()
                ->startArgumentBuilding('resultForwardFactory')
                    ->type('\Magento\Backend\Model\View\Result\ForwardFactory')
                ->finishBuilding()
            ->finishBuilding()
            ->startMethodBuilding('execute', $this->getExecuteBody())
                ->markAsPublic()
            ->finishBuilding()
            ->build();

        return new GeneratorResult(
            $this->wrapToFile($generator)->generate(),
            $this->generateFilePath($moduleNameEntity, $entityName, 'NewAction'),
            $this->generateEntityName($moduleNameEntity, $entityName, 'NewAction')
        );
    }

    private function getExecuteBody(): string
    {
        return '
/** @var \Magento\Framework\Controller\Result\Forward $resultForward */
$resultForward = $this->resultForwardFactory->create();
return $resultForward->forward(\'edit\');
        ';
    }

    private function getConstructorBody(): string
    {
        return '
$this->resultForwardFactory = $resultForwardFactory;
parent::__construct($context);
        ';
    }
}
