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
    /** public const GET_LIST = "
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
            eav_attribute ea ON ea.entity_type_id = (SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = 'catalog_product')
                LEFT JOIN
            catalog_product_entity_varchar cpv ON cpe.entity_id = cpv.row_id AND cpv.attribute_id = ea.attribute_id
                LEFT JOIN
            catalog_product_entity_int cpi ON cpe.entity_id = cpi.row_id AND cpi.attribute_id = ea.attribute_id
                LEFT JOIN
            catalog_product_entity_decimal cpd ON cpe.entity_id = cpd.row_id AND cpd.attribute_id = ea.attribute_id
                LEFT JOIN
            catalog_product_entity_datetime cpdt ON cpe.entity_id = cpdt.row_id AND cpdt.attribute_id = ea.attribute_id
                LEFT JOIN
            catalog_product_entity_text cpt ON cpe.entity_id = cpt.row_id AND cpt.attribute_id = ea.attribute_id


        GROUP BY
            cpe.entity_id,cpe.attribute_set_id, cpe.type_id, cpe.sku, cpe.has_options, cpe.required_options,cpe.created_at, cpe.updated_at, cpe.row_id, cpe.created_in, cpe.updated_in, cpe.mst_search_weight, ea.attribute_code, cpv.value, cpi.value, cpd.value, cpdt.value, cpt.value
    LIMIT %s, %s
"; */
public const GET_LIST = "
SELECT
    cpe.entity_id,
    cpe.attribute_set_id,
    cpe.type_id,
    cpe.sku,
    cpe.has_options,
    cpe.required_options,
    cpe.created_at,
    cpe.updated_at,
    cpe.row_id,
    cpe.created_in,
    cpe.updated_in,
    cpe.mst_search_weight,
    JSON_OBJECTAGG(attributes.attribute_code, attributes.value) AS custom_attributes
FROM
    catalog_product_entity cpe
LEFT JOIN (
    SELECT
        cpv.row_id,
        ea.attribute_code,
        cpv.value
    FROM
        catalog_product_entity_varchar cpv
    JOIN
        eav_attribute ea ON cpv.attribute_id = ea.attribute_id
    WHERE
        ea.entity_type_id = 4
   UNION ALL
    SELECT
        cpi.row_id,
        ea.attribute_code,
        CAST(cpi.value AS CHAR) AS value
    FROM
        catalog_product_entity_int cpi
    JOIN
        eav_attribute ea ON cpi.attribute_id = ea.attribute_id
    WHERE
        ea.entity_type_id = 4
   UNION ALL
    SELECT
        cpd.row_id,
        ea.attribute_code,
        CAST(cpd.value AS CHAR) AS value
    FROM
        catalog_product_entity_decimal cpd
    JOIN
        eav_attribute ea ON cpd.attribute_id = ea.attribute_id
    WHERE
        ea.entity_type_id = 4
    UNION ALL
    SELECT
        cpdt.row_id,
        ea.attribute_code,
        CAST(cpdt.value AS CHAR) AS value
    FROM
        catalog_product_entity_datetime cpdt
    JOIN
        eav_attribute ea ON cpdt.attribute_id = ea.attribute_id
    WHERE
        ea.entity_type_id = 4
    UNION ALL
    SELECT
        cpt.row_id,
        ea.attribute_code,
        cpt.value
    FROM
        catalog_product_entity_text cpt
    JOIN
        eav_attribute ea ON cpt.attribute_id = ea.attribute_id
    WHERE
        ea.entity_type_id = 4
) AS attributes ON cpe.row_id = attributes.row_id
GROUP BY cpe.row_id
LIMIT %s, %s
";
    public const COUNT = "SELECT COUNT(*) AS count FROM catalog_product_entity";
}
