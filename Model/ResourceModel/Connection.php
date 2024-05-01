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

namespace Omnipro\DataMigration\Model\ResourceModel;

use Exception;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Adapter\Pdo\MysqlFactory;
use Omnipro\DataMigration\Logger\Logger;

/**
 * This Connection class
 *
 * @package Omnipro\DataMigration\Model\ResourceModel
 * @class Connection
 * @version  1.0.3
 */
class Connection
{
    /**
     * @var array
     */
    private array $config;

    private ?Mysql $adapter = null;

    /**
     * Construct
     *
     * @param MysqlFactory $mysqlFactory
     * @param DBConfig $DBConfig
     * @param Logger $logger
     */
    public function __construct(
        private MysqlFactory $mysqlFactory,
        private DBConfig $DBConfig,
        private Logger $logger
    ) {
        $this->config = $this->DBConfig->get();
    }

    /**
     * Get connection
     *
     * @return Mysql
     */
    private function getConnection(): Mysql
    {
        if (!$this->adapter) {
            $this->adapter = $this->mysqlFactory->create(
                Mysql::class,
                $this->config
            );
        }

        return $this->adapter;
    }

    /**
     * Get data
     *
     * @param string $query
     * @return array
     */
    public function getData(string $query): array
    {
        $rows = [];
        $adapter = $this->getConnection();
        try {
            $result = $adapter->query($query);
            $rows = $result->fetchAll();
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }

        return $rows;
    }

    /**
     * Get count
     *
     * @param string $query
     * @return int
     */
    public function getCount(string $query): int
    {
        $count = 0;
        $adapter = $this->getConnection();
        try {
            $result = $adapter->query($query);
            $count = $result->fetchColumn();
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }

        return (int) $count;
    }
}
