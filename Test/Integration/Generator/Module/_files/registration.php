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
            'moduleName'    => 'Test/Module',
        ],
        [// generated data
            'destinationFile' => 'app/code/Test/Module/registration.php',
            'entityName'      => null,
            'content'         => '<?php

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, \'Test_Module\', __DIR__);',
        ],
    ],
];
