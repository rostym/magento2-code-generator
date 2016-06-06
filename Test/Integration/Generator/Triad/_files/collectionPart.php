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
            'modelClass'    => '\Test\Module\Model\Test',
            'resourceClass' => '\Test\Module\Model\ResourceModel\Test',
        ],
        [// generated data
            'destinationFile' => 'app/code/Test/Module/Model/ResourceModel/Test/Collection.php',
            'entityName'      => '\Test\Module\Model\ResourceModel\Test\Collection',
            'content'         => '<?php

namespace Test\Module\Model\ResourceModel\Test;

/**
 * Class Test
 *
 * @method \Test\Module\Model\ResourceModel\Test getResource()
 * @method \Test\Module\Model\Test[] getItems()
 * @method \Test\Module\Model\Test[] getItemsByColumnValue()
 * @method \Test\Module\Model\Test getFirstItem()
 * @method \Test\Module\Model\Test getLastItem()
 * @method \Test\Module\Model\Test getItemByColumnValue()
 * @method \Test\Module\Model\Test getItemById()
 * @method \Test\Module\Model\Test getNewEmptyItem()
 * @method \Test\Module\Model\Test fetchItem()
 * @method \Test\Module\Model\Test beforeAddLoadedItem()
 *
 * @property \Test\Module\Model\Test[] _items
 * @property \Test\Module\Model\ResourceModel\Test _resource
 *
 * @package Test\Module\Model\ResourceModel\Test\Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected $_eventPrefix = \'test_module_test_collection\';

    protected $_eventObject = \'test_module_test_collection_object\';

    protected function _construct()
    {
        $this->_init(\Test\Module\Model\Test::class, \Test\Module\Model\ResourceModel\Test::class);
    }


}

',
        ],
    ],
];
