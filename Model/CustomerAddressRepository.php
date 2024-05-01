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

namespace Omnipro\DataMigration\Model;

use Omnipro\DataMigration\Model\Query\CustomerAddress;
use Omnipro\DataMigration\Model\ResourceModel\Connection;

/**
 * This customer address class
 *
 * @package Omnipro\DataMigration\Model
 * @class CustomerAddressRepository
 * @since 1.0.3
 */
class CustomerAddressRepository
{
    /**
     * Construct
     *
     * @param ResourceModel\Connection $connection
     */
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * Get customer address list
     *
     * @param string $customerEmail
     * @return array
     */
    public function getList(string $customerEmail)
    {
        $query = sprintf(CustomerAddress::GET_LIST, $customerEmail);

        return $this->connection->getData($query);
    }

    /**
     * Count customers
     *
     * @return int
     */
    public function count(): int
    {
        $query = CustomerAddress::COUNT;

        return $this->connection->getCount($query);
    }
}
