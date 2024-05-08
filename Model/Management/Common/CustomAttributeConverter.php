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

namespace Omnipro\DataMigration\Model\Management\Common;

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
     * @param array $equivalences
     * @return array
     */
    public static function convert(
        array $oldCustomAttributes,
        array $equivalences
    ): array {
        $newCustomAttributes = [];
        foreach ($oldCustomAttributes as $oldAttributeKey => $oldAttributeValue) {
            if (array_key_exists($oldAttributeKey, $equivalences)) {
                $newAttributeKey = $equivalences[$oldAttributeKey];
                $newCustomAttributes[$newAttributeKey] = $oldAttributeValue;
            }
        }

        return $newCustomAttributes;
    }

    /** 
     * Convert attributes that do not have equivalences*
     * 
     * @param array $oldCustomAttributes
     * @param array $equivalences
     * @return array
    */
    public static function convertNativeAttributes(
        array $oldCustomAttributes,
        array $equivalences): array {

        $attributesWithoutEquivalence = [];
        foreach ($oldCustomAttributes as $oldAttributeKey => $oldAttributeValue) {
            // Check if the attribute key does NOT exist in the equivalences
            if (!array_key_exists($oldAttributeKey, $equivalences)) {
                // If it does not exist, add it to the new array
                $attributesWithoutEquivalence[$oldAttributeKey] = $oldAttributeValue;
            }
        }

        return $attributesWithoutEquivalence;
    }

    public static  function convertRequiredFields(array $fields) {

    }
}
