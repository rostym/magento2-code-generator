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
            'modelInterfaceName' => '\Test\Module\Api\Data\TestInterface',
        ],
        [
            'destinationFile' => 'app/code/Test/Module/Model/TestRepository.php',
            'entityName'      => '\Test\Module\Model\TestRepository',
            'content'         => '<?php

namespace Test\Module\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Class Test
 *
 * @package Test\Module\Model
 */
class TestRepository
{

    protected $resource = null;

    protected $testFactory = null;

    protected $collectionFactory = null;

    public function __construct(\Test\Module\Model\ResourceModel\Test $resource, \Test\Module\Model\TestFactory $testFactory, \Test\Module\Model\ResourceModel\Test\CollectionFactory $collectionFactory)
    {
        $this->resource = $resource;
        $this->testFactory = $testFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Save Test
     *
     * @param \Test\Module\Api\Data\TestInterface $test
     *
     * @return \Test\Module\Api\Data\TestInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Test\Module\Api\Data\TestInterface $test)
    {
        try {
            $this->resource->save($test);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($exception->getMessage()));
        }

        return $test;
    }

    /**
     * Retrieve Test
     *
     * @param int $testId
     *
     * @return \Test\Module\Api\Data\TestInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($testId)
    {
        $test = $this->testFactory->create();
        $this->resource->load($test, $testId);
        if (!$test->getId()) {
            throw new NoSuchEntityException(__(\'Test with id "%1" does not exist.\', $testId));
        }

        return $test;
    }

    /**
     * Retrieve entity matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Test\Module\Api\Data\TestInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: \'eq\';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                     $sortOrder->getField(),
                     ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? \'ASC\' : \'DESC\'
                );
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        return $collection->getItems();
    }

    /**
     * Delete Test
     *
     * @param \Test\Module\Api\Data\TestInterface $test
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Test\Module\Api\Data\TestInterface $test)
    {
        try {
            $this->resource->delete($test);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete entity by ID.
     *
     * @param int $testId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($testId)
    {
        return $this->delete($this->getById($testId));
    }


}

',
        ],
    ],
];

