<?php
/**
 * Copyright (c) 2024 - Omnipro (https://www.omni.pro/)
 *
 * @author      Cesar Robledo <cesar.robledo@omni.pro>
 * @date        25/04/2024, 16:00
 * @category    Omnipro
 * @module      Omnipro/DataMigration
 */

declare(strict_types=1);

namespace Omnipro\DataMigration\Model\Synchronize;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Omnipro\DataMigration\Model\CustomerAddressRepository as OldCustomerAddressRepository;
use Omnipro\DataMigration\Model\Management\CustomerAddress as CustomerAddressManagement;
use Omnipro\DataMigration\Model\Status;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Customer\Model\ResourceModel\Customer\Collection as CustomerCollection;

/**
 * This customer class
 *
 * @package Omnipro\DataMigration\Model\Synchronize
 * @class Customer
 * @since 1.0.3
 */
class CustomerAddress
{
    const PAGE_SIZE = 1000;
    const PAGE_INIT = 1;

    /**
     * Construct
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param OldCustomerAddressRepository $oldCustomerAddressRepository
     * @param CustomerCollectionFactory $customerCollectionFactory
     * @param CustomerAddressManagement $customerAddressManagement
     * @param Status $status
     */
    public function __construct(
        private CustomerRepositoryInterface  $customerRepository,
        private OldCustomerAddressRepository $oldCustomerAddressRepository,
        private CustomerCollectionFactory    $customerCollectionFactory,
        private CustomerAddressManagement    $customerAddressManagement,
        private Status                       $status
    ){
    }

    /**
     * Main process
     *
     * @return Status
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function process(): Status
    {
        $totalAddress = $this->oldCustomerAddressRepository->count();
        $customerCollection = $this->getCollection();
        $total = $customerCollection->getSize();
        $totalPages = ceil($total / self::PAGE_SIZE);
        $page = self::PAGE_INIT;
        $this->status->setTotal($totalAddress);
        while ($page <= $totalPages) {
            $collection = $this->loadCollection($customerCollection, $page, self::PAGE_SIZE);
            /** @var CustomerInterface $customer */
            foreach ($collection as $customer) {
                $customer = $this->customerRepository->get($customer->getEmail());
                $customerAddressRows = $this->oldCustomerAddressRepository->getList(
                    $customer->getEmail()
                );
                $this->customerAddressManagement->bulkCreate(
                    $customer,
                    $customerAddressRows,
                    $this->status
                );
            }
            $page++;
        }

        return $this->status;
    }

    /**
     * Get customer collection
     *
     * @return CustomerCollection
     */
    private function getCollection(): CustomerCollection
    {
        return $this->customerCollectionFactory->create();
    }

    /**
     * Load collection with pagination
     *
     * @param CustomerCollection $collection
     * @param int $page
     * @param int $pageSize
     * @return mixed
     */
    private function loadCollection(
        CustomerCollection $collection,
        int $page,
        int $pageSize
    ): CustomerCollection {
        $collection->clear();
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $collection->load();
        return $collection;
    }
}
