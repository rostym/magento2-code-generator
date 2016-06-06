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
            'entityName'    => 'Test',
            'tableName'     => 'all_data_types'
        ],
        [// generated data
            'destinationFile' => 'app/code/Test/Module/Model/ResourceModel/Test.php',
            'entityName'      => '\Test\Module\Model\ResourceModel\Test',
            'content'         => '<?php

namespace Test\Module\Model\ResourceModel;

/**
 * Class Test
 *
 * @package \Test\Module\Model\ResourceModel
 */
class Test extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init(\'all_data_types\', \'id\');
    }


}

',
        ],
    ],
];
