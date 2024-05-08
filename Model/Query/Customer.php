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
 * This customer class
 *
 * @package Omnipro\DataMigration\Model\Query
 * @since version 1.0.3
 */
class Customer
{
    public const COUNT = 'SELECT COUNT(*) AS count FROM customer_entity';
    public const GET_LIST = "SELECT
    ce.*,
    JSON_OBJECTAGG(ea.attribute_code, CASE ea.backend_type
                                          WHEN 'varchar' THEN cev.value
                                          WHEN 'int' THEN cei.value
                                          ELSE NULL
    END) AS custom_attributes
    FROM
        customer_entity ce
            LEFT JOIN
        eav_attribute ea ON ea.entity_type_id = (SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = 'customer') AND ea.backend_type != 'static'
            LEFT JOIN
        customer_entity_varchar cev ON ce.entity_id = cev.entity_id AND cev.attribute_id = ea.attribute_id AND ea.backend_type = 'varchar'
            LEFT JOIN
        customer_entity_int cei ON ce.entity_id = cei.entity_id AND cei.attribute_id = ea.attribute_id AND ea.backend_type = 'int'
    GROUP BY
        ce.entity_id LIMIT %s, %s";
 /*
    public const GET_LIST = "SELECT
    ce.*,
    JSON_OBJECTAGG(ea.attribute_code,
        CASE ea.backend_type
            WHEN 'varchar' THEN cev.value
            WHEN 'int' THEN cei.value
            WHEN 'decimal' THEN ced.value
            WHEN 'datetime' THEN cedt.value
            WHEN 'text' THEN cet.value
            ELSE NULL
        END
    ) AS custom_attributes
FROM
    customer_entity ce
        LEFT JOIN
    customer_entity_varchar cev ON ce.entity_id = cev.entity_id
        LEFT JOIN
    customer_entity_int cei ON ce.entity_id = cei.entity_id
        LEFT JOIN
    customer_entity_decimal ced ON ce.entity_id = ced.entity_id
        LEFT JOIN
    customer_entity_datetime cedt ON ce.entity_id = cedt.entity_id
        LEFT JOIN
    customer_entity_text cet ON ce.entity_id = cet.entity_id
        LEFT JOIN
    eav_attribute ea ON cev.attribute_id = ea.attribute_id
        OR cei.attribute_id = ea.attribute_id
        OR ced.attribute_id = ea.attribute_id
        OR cedt.attribute_id = ea.attribute_id
        OR cet.attribute_id = ea.attribute_id
GROUP BY
    ce.entity_id
LIMIT %s, %s
";
 */
}
