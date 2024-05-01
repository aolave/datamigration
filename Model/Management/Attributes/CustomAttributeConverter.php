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
 * This custom attribute converter
 *
 * @class CustomAttributeConverter
 * @version  1.0.3
 */
class CustomAttributeConverter
{
    /**
     * Convert attributes
     *
     * @param array $oldCustomAttributes
     * @return array
     */
    public static function convert(array $oldCustomAttributes): array
    {
        $newCustomAttributes = [];
        foreach ($oldCustomAttributes as $oldAttributeKey => $oldAttributeValue) {
            if (isset(Equivalences::GET[$oldAttributeKey])) {
                $newAttributeKey = Equivalences::GET[$oldAttributeKey];
                $newCustomAttributes[$newAttributeKey] = $oldAttributeValue;
            }
        }

        return $newCustomAttributes;
    }
}
