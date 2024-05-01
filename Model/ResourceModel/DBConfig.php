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
            'host'           => $this->config->getGeneralHost(),
            'dbname'         => $this->config->getGeneralDatabase(),
            'username'       => $this->config->getGeneralUser(),
            'password'       => $this->config->getGeneralPassword(),
            'model'          => 'mysql4',
            'engine'         => 'innodb',
            'initStatements' => 'SET NAMES utf8;',
            'active'         => '1',
            'profiler'       => [
                'class'   => Profiler::class,
                'enabled' => true,
            ]
        ];
    }
}
