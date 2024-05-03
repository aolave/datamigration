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

use Magento\Framework\DB\Profiler;
use Omnipro\DataMigration\Helper\Config;

/**
 * This database config class
 *
 * @package Omnipro\DataMigration\Model\ResourceModel
 * @class DBConfig
 * @version  1.0.3
 */
class DBConfig
{
    /**
     * Construct
     *
     * @param Config $config
     */
    public function __construct(
        private Config $config
    ) {
    }

    /**
     * Get db config
     *
     * @return array
     */
    public function get(): array
    {
        return [
            'db_host' => $this->config->getGeneralHost(),
            'db_name' => $this->config->getGeneralDatabase(),
            'db_user' => $this->config->getGeneralUser(),
            'db_password' => $this->config->getGeneralPassword(),
            'db_port' => $this->config->getGeneralPort(),
            'ssh_host' => $this->config->getSshHost(),
            'ssh_user' => $this->config->getSshUser(),
            'ssh_password' => $this->config->getSshPassword(),
            'ssh_port' => $this->config->getSshPort(),
        ];
    }
}
