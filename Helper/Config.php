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

namespace Omnipro\DataMigration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * This Help getting module manager settings
 *
 * @class Config
 * @since version 1.0.1
 */
class Config extends AbstractHelper
{
    private const XML_PATH_OMNIPRO_BASE = 'omnipro/';
    public const XML_PATH_CONNECTION_GENERAL_HOST = 'connection_general/host';
    public const XML_PATH_CONNECTION_GENERAL_DATABASE = 'connection_general/database';
    public const XML_PATH_CONNECTION_GENERAL_USER = 'connection_general/user';
    public const XML_PATH_CONNECTION_GENERAL_PASSWORD = 'connection_general/password';
    public const XML_PATH_CONNECTION_SSH_HOST = 'connection_ssh/host';
    public const XML_PATH_CONNECTION_SSH_USER = 'connection_ssh/user';
    public const XML_PATH_CONNECTION_SSH_PASSWORD = 'connection_ssh/password';

    /**
     * Get config value
     *
     * @param string $path
     * @param int|null $store
     * @return mixed
     */
    public function getValue(string $path, int $store = null): mixed
    {
        $path = sprintf(
            '%s%s' ,
            self::XML_PATH_OMNIPRO_BASE,
            $path
        );

        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE, $store
        );
    }

    /**
     * Get general connection host
     *
     * @param int|null $store
     * @return string
     */
    public function getGeneralHost(int $store = null): string
    {
        $apiKey = $this->getValue(
            self::XML_PATH_CONNECTION_GENERAL_HOST,
            $store
        ) ?? '';

        return (string) $apiKey;
    }

    /**
     * Get general connection database
     *
     * @param int|null $store
     * @return string
     */
    public function getGeneralDatabase(int $store = null): string
    {
        $apiKey = $this->getValue(
            self::XML_PATH_CONNECTION_GENERAL_DATABASE,
            $store
        ) ?? '';

        return (string) $apiKey;
    }

    /**
     * Get general connection user
     *
     * @param int|null $store
     * @return string
     */
    public function getGeneralUser(int $store = null): string
    {
        $apiKey = $this->getValue(
            self::XML_PATH_CONNECTION_GENERAL_USER,
            $store
        ) ?? '';

        return (string) $apiKey;
    }

    /**
     * Get general connection password
     *
     * @param int|null $store
     * @return string
     */
    public function getGeneralPassword(int $store = null): string
    {
        $apiKey = $this->getValue(
            self::XML_PATH_CONNECTION_GENERAL_PASSWORD,
            $store
        ) ?? '';

        return (string) $apiKey;
    }

    /**
     * Get ssh connection host
     *
     * @param int|null $store
     * @return string
     */
    public function getSshHost(int $store = null): string
    {
        $apiKey = $this->getValue(
            self::XML_PATH_CONNECTION_SSH_HOST,
            $store
        ) ?? '';

        return (string) $apiKey;
    }

    /**
     * Get ssh connection user
     *
     * @param int|null $store
     * @return string
     */
    public function getSshUser(int $store = null): string
    {
        $apiKey = $this->getValue(
            self::XML_PATH_CONNECTION_SSH_USER,
            $store
        ) ?? '';

        return (string) $apiKey;
    }

    /**
     * Get ssh connection password
     *
     * @param int|null $store
     * @return string
     */
    public function getSshPassword(int $store = null): string
    {
        $apiKey = $this->getValue(
            self::XML_PATH_CONNECTION_SSH_PASSWORD,
            $store
        ) ?? '';

        return (string) $apiKey;
    }
}
