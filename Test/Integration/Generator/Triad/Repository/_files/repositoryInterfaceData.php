<?php
/**
 * This file is part of Code Generator for Magento.
 * (c) 2016. Rostyslav Tymoshenko <krifollk@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    [
        [
            'moduleName'         => 'Test/Module',
            'entityName'         => 'Test',
            'modelInterfaceName' => '\Test\Module\Model\Test',
        ],
        [
            'destinationFile' => 'app/code/Test/Module/Api/TestRepositoryInterface.php',
            'entityName'      => '\Test\Module\Api\TestRepositoryInterface',
            'content'         => '<?php

namespace Test\Module\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface Test
 *
 * @package \Test\Module\Api
 */
interface TestRepositoryInterface
{

    /**
     * Save Test
     *
     * @param \Test\Module\Model\Test $test
     *
     * @return \Test\Module\Model\Test
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Test\Module\Model\Test $test);

    /**
     * Retrieve Test
     *
     * @param int $testId
     *
     * @return \Test\Module\Model\Test
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($testId);

    /**
     * Retrieve entity matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Test\Module\Model\Test[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Test
     *
     * @param \Test\Module\Model\Test $test
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Test\Module\Model\Test $test);

    /**
     * Delete entity by ID.
     *
     * @param int $testId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($testId);

}

',
        ],
    ],
];

