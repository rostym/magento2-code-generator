<?php

namespace Krifollk\CodeGenerator\Model\Generator;

use InvalidArgumentException;

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

    /**
     * @param string $moduleName
     * @param string $entityName
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function generateDataSourceName($moduleName, $entityName)
    {
        self::validateModuleName($moduleName);
        return sprintf('%s_data_source', self::generateListingName($moduleName, $entityName));
    }

    private static function validateModuleName($moduleName)
    {
        if (!preg_match('/[A-Z]+[A-Za-z0-9]+_[A-Z]+[A-Z0-9a-z]+/', $moduleName)) {
            throw new InvalidArgumentException('Wrong module name. Example: Test_Module');
        }
    }

    /**
     * @param string $moduleName
     * @param string $entityName
     *
     * @return string
     */
    public static function generateListingName($moduleName, $entityName)
    {
        self::validateModuleName($moduleName);
        return sprintf('%s_%s_listing', strtolower($moduleName), mb_strtolower($entityName));
    }
}
