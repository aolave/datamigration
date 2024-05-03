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
use phpseclib3\Net\SSH2;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
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
     * @param DBConfig $DBConfig
     * @param Logger $logger
     */
    public function __construct(
        private DBConfig $DBConfig,
        private Logger $logger
    ) {
        $this->config = $this->DBConfig->get();
    }

    /**
     * Function get connection
     *
     * @return SSH2
     * @throws Exception
     */
    private function getConnection(): SSH2
    {
        $ssh = new SSH2($this->config['ssh_host'] . ':' . $this->config['ssh_port']);

        if (!$ssh->login($this->config['ssh_user'], $this->config['ssh_password'])) {
            throw new Exception('SSH login failed');
        }

        if (!$ssh->isConnected()) {
            throw new Exception('SSH connection failed');
        }

        if (!$ssh->isAuthenticated()) {
            throw new Exception('SSH authentication failed');
        }

        return $ssh;
    }

    /**
     * Function get mysql data
     *
     * @param string $query
     * @return array
     * @throws Exception
     */
    public function getMysqlData(string $query): array
    {
        $result = [];
        $response = $this->sshRemoteMysql($query);

        if (!empty($response)) {
            $lines = explode("\n", trim($response));
            $header = array_shift($lines);
            $keys = explode("\t", $header);

            foreach ($lines as $line) {
                $parts = explode("\t", $line);
                $formatted_line = array_combine($keys, $parts);
                $result[] = $formatted_line;
            }
        }

        return $result;
    }

    /**
     * Function string replace warning
     *
     * @param string $query
     * @return string
     * @throws Exception
     */
    public function sshRemoteMysql(string $query): string
    {
        $ssh = $this->getConnection();

        $command = sprintf(
            'mysql -u%s -p%s -D%s -P%s -r -e%s',
            escapeshellarg($this->config['db_user']),
            escapeshellarg($this->config['db_password']),
            escapeshellarg($this->config['db_name']),
            escapeshellarg($this->config['db_port']),
            escapeshellarg($query)
        );

        return str_replace("mysql: [Warning] Using a password on the command line interface can be insecure.\n",
            '',
            $ssh->exec($command)
        );
    }

    /**
     * Get data
     *
     * @param string $query
     * @return array
     * @throws Exception
     */
    public function getData(string $query): array
    {
        try {
            $result = $this->getMysqlData($query);
            $rows = $result;
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $rows;
    }

    /**
     * Get count
     *
     * @param string $query
     * @return int
     * @throws Exception
     */
    public function getCount(string $query): int
    {
        $count = 0;
        try {
            $result = $this->getMysqlData($query);
            if (!empty($result)) {
                $count = $result[0]['count'];
            }

        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return (int) $count;
    }
}
