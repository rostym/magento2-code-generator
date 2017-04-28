<?php

declare(strict_types=1);

namespace Krifollk\CodeGenerator\Model\Generator;

use Krifollk\CodeGenerator\Model\ModuleNameEntity;

/**
 * Class NameUtil
 *
 * @package Krifollk\CodeGenerator\Model\Generator
 */
final class NameUtil
{
    private function __construct()
    {
    }

    public static function generateDataSourceName(ModuleNameEntity $moduleNameEntity, $entityName): string
    {
        return sprintf('%s_data_source', self::generateListingName($moduleNameEntity, $entityName));
    }

    public static function generateListingName(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return sprintf('%s_%s_listing', strtolower($moduleNameEntity->value()), mb_strtolower($entityName));
    }

    public static function camelize(string $columnName): string
    {
        return str_replace('_', '', ucwords($columnName, '_'));
    }

    public static function generateResourceName(ModuleNameEntity $moduleNameEntity, string $entityName): string
    {
        return sprintf('\%s\Model\ResourceModel\%s', $moduleNameEntity->asPartOfNamespace(), $entityName);
    }

    public static function generateCollectionName(ModuleNameEntity $moduleNameEntity, $entityName): string
    {
        return sprintf('\%s\Model\ResourceModel\%s\Collection', $moduleNameEntity->asPartOfNamespace(), $entityName);
    }
}
