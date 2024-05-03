<?php
/**
 * Copyright © 2024 - Omnipro (https://www.omni.pro/)
 *
 * @author      Karen Ceballos <karen.ceballos@omni.pro>
 * @date        30/04/2024, 16:00
 * @category    Omnipro
 * @module      Omnipro/DataMigration
 */

declare(strict_types=1);

namespace Omnipro\DataMigration\Model\Query;

/**
 * This product class
 *
 * @package Omnipro\DataMigration\Model\Query
 * @since version 1.0.3
 */
class Product
{
    public const GET_LIST = "
    SELECT
            cpe.*,
            JSON_OBJECTAGG(ea.attribute_code, CASE ea.backend_type
                                                  WHEN 'varchar' THEN cpv.value
                                                  WHEN 'int' THEN cpi.value
                                                  WHEN 'decimal' THEN cpd.value
                                                  WHEN 'datetime' THEN cpdt.value
                                                  WHEN 'text' THEN cpt.value
                                                  ELSE NULL
                END) AS custom_attributes
        FROM
            catalog_product_entity cpe
                LEFT JOIN
            catalog_product_entity_varchar cpv ON cpe.entity_id = cpv.row_id
                LEFT JOIN
            catalog_product_entity_int cpi ON cpe.entity_id = cpi.row_id
                LEFT JOIN
            catalog_product_entity_decimal cpd ON cpe.entity_id = cpd.row_id
                LEFT JOIN
            catalog_product_entity_datetime cpdt ON cpe.entity_id = cpdt.row_id
                LEFT JOIN
            catalog_product_entity_text cpt ON cpe.entity_id = cpt.row_id
                LEFT JOIN
            eav_attribute ea ON cpv.attribute_id = ea.attribute_id
                OR cpi.attribute_id = ea.attribute_id
                OR cpd.attribute_id = ea.attribute_id
                OR cpdt.attribute_id = ea.attribute_id
                OR cpt.attribute_id = ea.attribute_id
        WHERE ea.entity_type_id = (
            SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = 'catalog_product'
        )
        GROUP BY
            cpe.entity_id
    LIMIT %s, %s
";
    public const COUNT = "SELECT COUNT(*) AS count FROM product_entity";
}
