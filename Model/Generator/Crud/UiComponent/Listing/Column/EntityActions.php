<?php

namespace Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\Listing\Column;

use Krifollk\CodeGenerator\Model\ClassBuilder;
use Krifollk\CodeGenerator\Model\Generator\AbstractGenerator;
use Krifollk\CodeGenerator\Model\GeneratorResult;
use Zend\Code\Generator\FileGenerator;

/**
 * Class EntityActions
 *
 * @package Krifollk\CodeGenerator\Model\Generator\Crud\UiComponent\Listing\Column
 */
class EntityActions extends AbstractGenerator
{
    const FILE_PATH_PATTERN = '%s/app/code/%s/Model/UiComponent/Listing/Column/%sActions.php';
    const ENTITY_NAME_PATTERN = '\%s\Model\UiComponent\Listing\Column\%sActions';
    const NAMESPACE_PATTERN = '\%s\Model\UiComponent\Listing\Column';

    /**
     * @inheritdoc
     */
    public function generate(array $arguments = [])
    {
        $this->checkArguments($arguments);

        $classGenerator = new ClassBuilder($this->generateEntityName($arguments['moduleName'], $arguments['entityName']));
        $deleteUrl = $this->getActionUrl($arguments['moduleName'], $arguments['entityName'], 'delete');
        $editUrl = $this->getActionUrl($arguments['moduleName'], $arguments['entityName'], 'edit');

        /** @var \Magento\Framework\Code\Generator\ClassGenerator $generator */
        $generator = $classGenerator
            ->extendedFrom('\Magento\Ui\Component\Listing\Columns\Column')
            ->usesNamespace($this->generateNamespace($arguments['moduleName']))
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
            ->startMethodBuilding(
                    'prepareDataSource',
                    $this->getPrepareDataSourceBody($arguments['idFieldName'], $editUrl, $deleteUrl)
                )
                ->markAsPublic()
                ->startArgumentBuilding('dataSource')
                    ->type('array')
                ->finishBuilding()
            ->finishBuilding()
            ->build();

        return new GeneratorResult(
            $this->wrapToFile($generator)->generate(),
            $this->generateFilePath($arguments['moduleName'], $arguments['entityName']),
            $this->generateEntityName($arguments['moduleName'], $arguments['entityName'])
        );
    }

    private function getConstructorBody()
    {
        return '
$this->urlBuilder = $urlBuilder;
parent::__construct($context, $uiComponentFactory, $components, $data);
        ';
    }

    protected function generateFilePath($moduleName, $entityName)
    {
        return sprintf(self::FILE_PATH_PATTERN, $this->getBasePath(), $moduleName, $entityName);
    }

    protected function generateEntityName($moduleName, $entityName)
    {
        return sprintf(self::ENTITY_NAME_PATTERN, str_replace('/', '\\', $moduleName), $entityName);
    }

    protected function generateNamespace($moduleName)
    {
        return sprintf(self::NAMESPACE_PATTERN, str_replace('/', '\\', $moduleName));
    }

    protected function requiredArguments(): array
    {
        return ['moduleName', 'entityName', 'idFieldName'];
    }

    private function wrapToFile($generatorObject)
    {
        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($generatorObject);

        return $fileGenerator;
    }

    private function getActionUrl($moduleName, $entityName, $action)
    {
        return sprintf(
            '%s/%s/%s',
            str_replace('/', '_', mb_strtolower($moduleName)), mb_strtolower($entityName),
            $action
        );
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
