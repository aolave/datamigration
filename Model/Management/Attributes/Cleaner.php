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

namespace Omnipro\DataMigration\Model\Management\Attributes;

/**
 * This Clean array fields
 *
 * @class Cleaner
 * @version  1.0.3
 */
class Cleaner
{
    /**
     * Clean array fields
     *
     * @param array $data
     * @return array
     */
    public static function clean(array $data) : array
    {
        foreach (CleanFieldList::GET as $key) {
            if (isset($data[$key])) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}
