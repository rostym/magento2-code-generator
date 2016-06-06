<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    [
        [ // input data
            'moduleName' => 'Test/Module',
        ],
        [// generated data
            'destinationFile' => 'app/code/Test/Module/etc/module.xml',
            'entityName'      => null,
            'content'         => '<?xml version="1.0" encoding="UTF-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
  <module name="Test_Module" setup_version="0.1.0"/>
</config>
',
        ],
    ],
];
