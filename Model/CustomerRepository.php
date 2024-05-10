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

use Exception;
use Omnipro\DataMigration\Model\Query\Customer;
use Omnipro\DataMigration\Model\ResourceModel\Connection;

/**
 * This customer class
 *
 * @package Omnipro\DataMigration\Model
 * @class CustomerRepository
 * @since 1.0.3
 */
class CustomerRepository
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
     * Get customer list
     *
     * @param int $page
     * @param int $pageSize
     * @return array
     * @throws Exception
     */
    public function getList(int $page, int $pageSize): array
    {
        $query = sprintf(Customer::GET_LIST, $pageSize, $page);

        return $this->connection->getData($query);
    }

    /**
     * Count customers
     *
     * @return int
     * @throws Exception
     */
    public function count(): int
    {
        $query = Customer::COUNT;

        return $this->connection->getCount($query);
    }
}
