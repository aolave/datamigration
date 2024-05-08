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

namespace Omnipro\DataMigration\Model\Management\CustomerAddress\Attributes;

/**
 * This array equivalences
 *
 * @class Equivalences
 * @version  1.0.3
 */
class Equivalences
{
    //Array key attributes : key => value, olb_code_Attribute => new_code_attribute
    public const GET = [
        'alias' => 'alias', //new in 2.4.6
        'colonia' => 'colony',
        'entrecalle1' => 'between_first_street',
        'entrecalle2' => 'between_second_street',
        'lat_long' => 'lat_long', //latitude,longitude
        'latitude' => 'latitude',
        'longitude' => 'longitude',
        'municipio' => 'municipality',
        'no_ext' => 'external_number',
        'no_int' => 'internal_number',
        'referencia' => 'references'
    ];
}
