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

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

/**
 * This console class
 *
 * @package Omnipro\DataMigration\Model
 * @class Console
 * @since 1.0.3
 */
class Console
{
    /**
     * Print table
     *
     * @param array $headers
     * @param array $rows
     * @param OutputInterface $output
     * @return void
     */
    public static function printTable(
        array $headers,
        array $rows,
        OutputInterface $output
    ) {
        $table = new Table($output);
        $table->setHeaders($headers);
        $table->setRows([$rows]);
        $table->render();
    }
}
