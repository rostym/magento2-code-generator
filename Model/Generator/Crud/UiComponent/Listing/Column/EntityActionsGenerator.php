<?php

declare(strict_types=1);

/**
 * This file is part of Code Generator for Magento.
 * (c) 2017. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\Listing\Column;

use Krifollk\CodeGenerator\Api\GeneratorResultInterface;
use Krifollk\CodeGenerator\Model\ClassBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Krifollk\CodeGenerator\Model\ModuleNameEntity;
use Krifollk\CodeGenerator\Model\TableDescriber\Result;
use Zend\Code\Generator\FileGenerator;

/**
 * Class EntityActions
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\Listing\Column
 */
class EntityActionsGenerator extends AbstractGenerator
{
    const NAMESPACE_PATTERN = '\%s\Model\UiComponent\Listing\Column';

    /**
     * @inheritdoc
     */
    protected function requiredArguments(): array
    {
        return ['entityName', 'tableDescriberResult'];
    }

    /**
     * @inheritdoc
     */
    protected function internalGenerate(
        ModuleNameEntity $moduleNameEntity,
        array $additionalArguments = []
    ): GeneratorResultInterface {
        $entityName = $additionalArguments['entityName'];
        /** @var Result $tableDescriberResult */
        $tableDescriberResult = $additionalArguments['tableDescriberResult'];

        $className = sprintf('\%s\Model\UiComponent\Listing\Column\%sActions', $moduleNameEntity->asPartOfNamespace(), $entityName);

        $classGenerator = new ClassBuilder($className);
        $deleteUrl = $this->getActionUrl($moduleNameEntity, $entityName, 'delete');
        $editUrl = $this->getActionUrl($moduleNameEntity, $entityName, 'edit');

        /** @var \Magento\Framework\Code\Generator\ClassGenerator $generator */
        $generator = $classGenerator
            ->extendedFrom('\Magento\Ui\Component\Listing\Columns\Column')
            ->startPropertyBuilding('urlBuilder')
                ->markAsPrivate()
            ->finishBuilding()
            ->startMethodBuilding('__construct', $this->getConstructorBody())
                ->markAsPublic()

                ->startArgumentBuilding('context')
                    ->type('\Magento\Framework\View\Element\UiComponent\ContextInterface')
                ->finishBuilding()

                ->startArgumentBuilding('uiComponentFactory')
                    ->type('\Magento\Framework\View\Element\UiComponentFactory')
                ->finishBuilding()

                ->startArgumentBuilding('urlBuilder')
                    ->type('\Magento\Framework\UrlInterface')
                ->finishBuilding()

                ->startArgumentBuilding('components')
                    ->type('array')
                    ->value([])
                ->finishBuilding()

                ->startArgumentBuilding('data')
                    ->type('array')
                    ->value([])
                ->finishBuilding()

            ->finishBuilding()
            ->startMethodBuilding('prepareDataSource',
                $this->getPrepareDataSourceBody($tableDescriberResult->primaryColumn()->name(), $editUrl, $deleteUrl)
            )
                ->markAsPublic()
                ->startArgumentBuilding('dataSource')
                    ->type('array')
                ->finishBuilding()
            ->finishBuilding()
            ->build();

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($generator);

        return new GeneratorResult(
            $fileGenerator->generate(),
            sprintf('%s/Model/UiComponent/Listing/Column/%sActions.php', $moduleNameEntity->asPartOfPath(), $entityName),
            $className
        );
    }

    private function getConstructorBody()
    {
        return '
$this->urlBuilder = $urlBuilder;
parent::__construct($context, $uiComponentFactory, $components, $data);
        ';
    }

    private function getActionUrl(ModuleNameEntity $moduleName, $entityName, $action): string
    {
        return sprintf('%s/%s/%s', mb_strtolower($moduleName->value()), mb_strtolower($entityName), $action);
    }

    private function getPrepareDataSourceBody($idFieldName, $editUrlPath, $deleteUrlPath)
    {
        return "
if (!isset(\$dataSource['data']['items'])) {
    return \$dataSource;
}

foreach (\$dataSource['data']['items'] as &\$item) {
    if (!isset(\$item['$idFieldName'])) { 
        continue;
    }
    \$item[\$this->getData('name')] = [
        'edit' => [
            'href' => \$this->urlBuilder->getUrl(
                '$editUrlPath',
                [
                    'id' => \$item['$idFieldName']
                ]
            ),
            'label' => __('Edit')
        ],
        'delete' => [
            'href' => \$this->urlBuilder->getUrl(
                '$deleteUrlPath',
                [
                    'id' => \$item['$idFieldName']
                ]
            ),
            'label' => __('Delete'),
            'confirm' => [
                'title' => __('Delete \"\${ $.\$data.title }\"'),
                'message' => __('Are you sure you wan\\'t to delete a \"\${ $.\$data.title }\" record?')
            ]
        ]
    ];
}

return \$dataSource;
        ";
    }
}
