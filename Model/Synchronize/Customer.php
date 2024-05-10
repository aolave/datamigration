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
    const PAGE_SIZE = 5000;
    // const PAGE_INIT = 22001 //error key and part;
    //const PAGE_INIT = 148070 //error registro vacios;

    const PAGE_INIT = 148070;

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
        $totalPages = ceil($total);
        $page = self::PAGE_INIT;
        $pageSize = self::PAGE_SIZE;
        $this->status->setTotal($total);


        while ($page <= $totalPages) {

            $dataCustomers = [];
            $dataAttributes = [];

            $customerRows = $this->oldCustomerRepository->getList($page, $pageSize);

            foreach ($customerRows as $customerRow) {
                $page++;

                if($this->customerManagement->checkCustomer($customerRow)){
                    continue;
                }

                $dataCustomers[] = $this->customerManagement->prepareCustomerData($customerRow);
                $dataAttributes[] = $customerRow;
            }

            $result = $this->customerManagement->createMultiple($dataCustomers, $dataAttributes);

            $this->status->increment($result);
        }

        return $this->status;
    }
}
