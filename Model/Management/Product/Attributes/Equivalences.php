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

namespace Omnipro\DataMigration\Model\Management\Product\Attributes;

/**
 * This array equivalences
 *
 * @class Equivalences
 * @version  1.0.0
 */
class Equivalences
{
   //Array key attributes : key => value, olb_code_Attribute => new_code_attribute
   public const GET = [
    'allow_message' => 'allow_message',
    'allow_open_amount' => 'allow_open_amount',
    'aplicacion' => 'aplication',
    'baby_stages' => 'baby_stages', //new in magento 2.4.6
    'beneficios_usos' => 'benefits_or_uses',
    'catalog_extended' => 'catalog_extended',
    'characteristics' => 'characteristics',
    'content_cbd' => 'content_cbd',//new in magento 2.4.6, has_cbd
    'dermascan_icon' => 'dermascan_icon',//new in magento 2.4.6
    'derma_icon_dermafan' => 'derma_icon_dermafan',
    'derma_icon_dermascan' => 'derma_icon_dermascan',
    'derma_icon_teleorientacion' => 'derma_icon_teleorientacion',
    'donaciones' => 'donaciones',//new in magento 2.4.6
    'donations_campaigns' => 'donations_campaigns',
    'edades' => 'ages',
    'email_template' => 'email_template',
    'es_donativo_teleton' => 'es_donativo_teleton',
    'etapa' => 'etapa',//new in magento 2.4.6
    'etapa_medicamento' => 'etapa_medicamento',//new in magento 2.4.6
    'features' => 'features',//new in magento 2.4.6
    'genero' => 'genero',//new in magento 2.4.6
    'giftcard_amounts' => 'giftcard_amounts',
    'giftcard_type' => 'giftcard_type',
    'gift_card_type' => 'gift_card_type',//new in magento 2.4.6
    'gift_code_sets' => 'gift_code_sets',//new in magento 2.4.6
    'gift_dropdown' => 'gift_dropdown',//new in magento 2.4.6
    'gift_from' => 'gift_from',//new in magento 2.4.6
    'gift_message_available' => 'gift_message_available',
    'gift_price' => 'gift_price',//new in magento 2.4.6
    'gift_price_type' => 'gift_price_type',//new in magento 2.4.6
    'gift_template_ids' => 'gift_template_ids',//new in magento 2.4.6
    'gift_to' => 'gift_to',//new in magento 2.4.6
    'gift_type' => 'gift_type',//new in magento 2.4.6
    'gift_value' => 'gift_value',//new in magento 2.4.6
    'gift_wrapping_available' => 'gift_wrapping_available',
    'gift_wrapping_price' => 'gift_wrapping_price',
    'icon_conoce_mas' => 'icon_conoce_mas',
    'icon_dental_scan' => 'icon_dental_scan',
    'icon_dental_test' => 'icon_dental_test',
    'ingredientes' => 'ingredients',
    'is_donation' => 'is_donation',
    'is_laboratory' => 'is_laboratory',
    'is_redeemable' => 'is_redeemable',
    'is_returnable' => 'is_returnable',
    'is_star_product' => 'is_star_product',
    'label_by_price' => 'label_by_price',//new in magento 2.4.6
    'laboratorio_filtrable' => 'laboratorio_filtrable',//new in magento 2.4.6
    'lifetime' => 'lifetime',
    'links_exist' => 'links_exist',
    'links_purchased_separately', 'links_purchased_separately',
    'links_title','links_title',
    'marca' => 'brand',
    'marca_derma' => 'marca_derma',//new in magento 2.4.6
    'marca_filtrable' => 'marca_filtrable',//new in magento 2.4.6
    'marca_propia' => 'own_brand',
    'max_shipment_time' => 'max_shipment_time',//new in magento 2.4.6
    'min_shipment_time' => 'min_shipment_time',//new in magento 2.4.6
    'monedero' => 'has_monedero', //has_monedero
    'mp_pickup_locations' => 'mp_pickup_locations',
    'old_id' => 'old_id',
    'open_amount_max' => 'open_amount_max',
    'open_amount_min' => 'open_amount_min',
    'over_the_counter' => 'over_the_counter',
    'page_layout' => 'page_layout',
    'preparation_rule_1' => 'preparation_rule_1',
    'preparation_rule_2' => 'preparation_rule_2',
    'preparation_rule_3' => 'preparation_rule_3',
    'prescription_label' => 'prescription_label', //prescription_required
    'price_type' => 'price_type',
    'price_view' => 'price_view',
    'propiedades' => 'properties',
    'proveedor' => 'provider',
    'quantity_and_stock_status' => 'quantity_and_stock_status',
    'recipe_type' => 'recipe_type',//new in magento 2.4.6
    'recomendar' => 'recomendar',
    'related_tgtr_position_behavior' => 'related_tgtr_position_behavior',
    'related_tgtr_position_limit' => 'related_tgtr_position_limit',
    'requires_recipe' => 'requires_recipe',//new in magento 2.4.6
    'result_return_time' => 'result_return_time',
    'rutina' => 'routine',
    'samples_title' => 'samples_title',
    'shipment_type' => 'shipment_type',
    'show_price_by_weigth' => 'show_price_by_weigth',
    'sku_type' => 'sku_type',
    'sold_qty' => 'sold_qty',//new in magento 2.4.6
    'studies_compose_it' => 'studies_compose_it',
    'subscription_active' => 'subscription_active',
    'swatch_image' => 'swatch_image',
    'tag_accumulate' => 'tag_accumulate',
    'teleorientacion_icon' => 'teleorientacion_icon',
    'test_type' => 'test_type',
    'textura' => 'texture',
    'tipo_piel' => 'skin_type',
    'ts_dimensions_height' => 'ts_dimensions_height',//new in magento 2.4.6
    'ts_dimensions_length' => 'ts_dimensions_length',//new in magento 2.4.6
    'ts_dimensions_width' => 'ts_dimensions_width',//new in magento 2.4.6
    'unit_price' => 'unit_price',//new in magento 2.4.6
    'upsell_tgtr_position_behavior' => 'upsell_tgtr_position_behavior',
    'upsell_tgtr_position_limit' => 'upsell_tgtr_position_limit',
    'use_config_allow_message' => 'use_config_allow_message',
    'use_config_email_template' => 'use_config_email_template',
    'use_config_is_redeemable' => 'use_config_is_redeemable',
    'use_config_lifetime' => 'use_config_lifetime',
    'vendor_name' => 'vendor_name',//new in magento 2.4.6
    'webpos_visible' => 'webpos_visible',//new in magento 2.4.6
    'weight_type' => 'weight_type',
    'wesupply_estimation_display' => 'wesupply_estimation_display'//new in magento 2.4.6
];
    public const CODE_ATTRIBUTESET = 'attribute_set_id';
    
    public const OPTIONS = [
        'attribute_set_id' => [
            4 => 4, //Default
            11 => 16, //Farmacia
            12 => 16
        ]
    ];

}
