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


/**
 * This status class
 *
 * @package Omnipro\DataMigration\Model
 * @class Status
 * @since 1.0.3
 */
class Status
{
    public const SUCCESS = 'success';
    public const FAILURE = 'error';
    public const EXISTS = 'exists';
    public const TOTAl = 'total';

    private array $status = [
        'headers' => ['Total', 'Total error', 'Total completed', 'Total exists'],
        'rows' => ['total' => 0, 'error' => 0, 'success' => 0, 'exists' => 0]
    ];

    /**
     * Get rows
     *
     * @return array
     */
    public function getRows(): array
    {
        return $this->status['rows'];
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->status['headers'];
    }

    /**
     * Set total
     *
     * @param int $total
     * @return void
     */
    public function setTotal(int $total): void
    {
        $this->status[self::TOTAl] = $total;
    }

    /**
     * Increment
     *
     * @param string $key
     * @return void
     */
    public function increment(string $key): void
    {
        if (array_key_exists($key, $this->status['rows'])) {
            $this->status['rows'][$key]++;
        }
    }
}
