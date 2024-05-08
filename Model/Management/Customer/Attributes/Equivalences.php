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

namespace Omnipro\DataMigration\Model\Management\Customer\Attributes;

/**
 * This array equivalences
 *
 * @class Equivalences
 * @version  1.0.3
 */
class Equivalences
{
    public const CODE_GENDER = 'gender';
    public const CODE_GROUPID = 'group_id';

    //Array key attributes : key => value, olb_code_Attribute => new_code_attribute
    public const GET = [
        'customer_locked' => 'lock_type',
        'customer_telephone' => 'cellphone',
        'external_id' => 'external_id', //new attribute in 2.4.6
        'mothers_surname' => 'mother_surname',
        'monedero' => 'monedero',
        'reward_update_notification' => 'reward_update_notification',
        'reward_warning_notification' => 'reward_warning_notification'
    ];

    public const OPTIONS = [
        self::CODE_GENDER => [
            1 => 1, //Male
            3 => 4, //Female
            5 => 7 //Not Specified
        ],
        self::CODE_GROUPID => [
            0 => 0, //NOT LOGGED IN
            1 => 1, //General
            2 => 2, //Wholesale
            3 => 3, //Retailer
            4 => 4, //NACIONAL
            7 => 7, //NUEVAS_APERTURAS
        ]
    ];
}
