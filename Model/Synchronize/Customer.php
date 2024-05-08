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

use Exception;
use Omnipro\DataMigration\Model\CustomerRepository as OldCustomerRepository;
use Omnipro\DataMigration\Model\Management\Customer as CustomerManagement;
use Omnipro\DataMigration\Model\Status;

/**
 * This customer class
 *
 * @package Omnipro\DataMigration\Model\Synchronize
 * @class Customer
 * @since 1.0.3
 */
class Customer
{
    const PAGE_SIZE = 500;
    const PAGE_INIT = 1;

    /**
     * Construct
     *
     * @param OldCustomerRepository $oldCustomerRepository
     * @param CustomerManagement $customerManagement
     * @param Status $status
     */
    public function __construct(
        private OldCustomerRepository $oldCustomerRepository,
        private CustomerManagement $customerManagement,
        private Status $status
    ){
    }

    /**
     * Main process
     *
     * @return Status
     * @throws Exception
     */
    public function process(): Status
    {
        $total = $this->oldCustomerRepository->count();
        $totalPages = ceil($total / self::PAGE_SIZE);
        $page = self::PAGE_INIT;
        $this->status->setTotal($total);
        while ($page <= $totalPages) {

            $customerRows = $this->oldCustomerRepository->getList($page, self::PAGE_SIZE);

            $customersData[] = $this->customerManagement->prepareCustomerData($customerRows);

            $result = $this->customerManagement->create($customersData);
            $this->status->increment($result);
            $page++;
        }

        return $this->status;
    }
}
