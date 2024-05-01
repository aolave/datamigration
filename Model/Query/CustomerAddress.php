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

namespace Omnipro\DataMigration\Model\Query;

/**
 * This customer address class
 *
 * @package Omnipro\DataMigration\Model\Query
 * @since version 1.0.3
 */
class CustomerAddress
{
    public const GET_LIST = "
        SELECT
            ca.*,
            ce.email,
            JSON_OBJECTAGG(ea.attribute_code, CASE ea.backend_type
                                                  WHEN 'varchar' THEN cav.value
                                                  WHEN 'int' THEN cai.value
                                                  WHEN 'decimal' THEN cad.value
                                                  WHEN 'datetime' THEN cadt.value
                                                  WHEN 'text' THEN cat.value
                                                  ELSE NULL
                END) AS custom_attributes
        FROM
            customer_address_entity ca
                LEFT JOIN
            (SELECT entity_id, email from customer_entity) ce ON ca.parent_id = ce.entity_id
                LEFT JOIN
            customer_address_entity_varchar cav ON ca.entity_id = cav.entity_id
                LEFT JOIN
            customer_address_entity_int cai ON ca.entity_id = cai.entity_id
                LEFT JOIN
            customer_address_entity_decimal cad ON ca.entity_id = cad.entity_id
                LEFT JOIN
            customer_address_entity_datetime cadt ON ca.entity_id = cadt.entity_id
                LEFT JOIN
            customer_address_entity_text cat ON ca.entity_id = cat.entity_id
                LEFT JOIN
            eav_attribute ea ON cav.attribute_id = ea.attribute_id
                OR cai.attribute_id = ea.attribute_id
                OR cad.attribute_id = ea.attribute_id
                OR cadt.attribute_id = ea.attribute_id
                OR cat.attribute_id = ea.attribute_id
        WHERE
            ca.entity_id IS NOT NULL AND ce.email IS NOT NULL AND ce.email = '%s'
        GROUP BY ca.entity_id
    ";

    public const COUNT = "SELECT COUNT(*) AS count FROM customer_address_entity";
}
