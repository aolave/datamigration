<?php
/**
 * Copyright © 2024 - Omnipro (https://www.omni.pro/)
 *
 * @author      karen Ceballos <karen.ceballos@omni.pro>
 * @date        02/05/2024, 09:00
 * @category    Omnipro
 * @module      Omnipro/DataMigration
 */

declare(strict_types=1);

namespace Omnipro\DataMigration\Model;

use Omnipro\DataMigration\Model\Query\Product;
use Omnipro\DataMigration\Model\ResourceModel\Connection;

/**
 * This customer class
 *
 * @package Omnipro\DataMigration\Model
 * @class CustomerRepository
 * @since 1.0.3
 */
class ProductRepository
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
     * Get product list
     *
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getList(int $page, int $pageSize)
    {
        $query = sprintf(Product::GET_LIST, $page, $pageSize);

        return $this->connection->getData($query);
    }

    /**
     * Count customers
     *
     * @return int
     */
    public function count(): int
    {
        $query = Product::COUNT;

        return $this->connection->getCount($query);
    }
}
