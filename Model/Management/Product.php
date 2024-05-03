<?php
/**
 * Copyright © 2024 - Omnipro (https://www.omni.pro/)
 *
 * @author      karen Ceballos <karen.ceballos@omni.pro>
 * @date        02/05/2024, 09:00
 * @category    Omnipro
 * @module      Omnipro/DataMigration
 */

declare(strict_types=1);

namespace Omnipro\DataMigration\Model\Management;

use Exception;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\Product\Type;
use Magento\Framework\Serialize\Serializer\Json as JsonSerialize;
use Omnipro\DataMigration\Logger\Logger;
use Omnipro\DataMigration\Model\Status;

/**
 * This Product class
 *
 * @package Omnipro\DataMigration\Model\Management
 * @class Product
 * @version  1.0.3
 */
class Product 
{
    /**
     * Construct
     *
     * @param ProductFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param JsonSerialize $jsonSerialize
     * @param Logger $logger
     */
    public function __construct(
        private CategoryLinkManagementInterface $categoryLinkManagement,
        private ProductFactory $productFactory,
        private ProductRepositoryInterface $productRepository,
        private JsonSerialize $jsonSerialize,
        private Logger $logger
    ) {
    }

    /**
     * Create product
     *
     * @param $itemObject
     * @return string
     */
    public function create($itemObject): string
    {
        $result = Status::FAILURE;
        // no validar existencia de producto si no es necesario

        $urlKey = $this->generateURL($itemObject->name);

        $newProduct = $this->productFactory->create();

        $newProduct->setSku($itemObject->sku);
        $newProduct->setName($itemObject->name);
        $newProduct->setAttributeSetId(0); //default 0
        $newProduct->setVisibility(4);
        $newProduct->setTaxClassId($itemObject->tax_class_id);
        $newProduct->setStatus($itemObject->status);
        $newProduct->setPrice(0);
        $newProduct->setQty(0);
        $newProduct->setTypeId(Type::TYPE_SIMPLE);
        $newProduct->setDescription($itemObject->long_description);
        $newProduct->setShortDescription($itemObject->short_description);
        $newProduct->setCustomAttributes($itemObject->custom_attributes);
        $newProduct->setUrlKey($urlKey);

        try {
            $this->productRepository->save($newProduct);
            $this->categoryLinkManagement->assignProductToCategories($itemObject->sku, $itemObject->category_ids);
            $result = Status::SUCCESS;
        } catch (Exception $e) {
            $this->logger->info( $e->getMessage());
        }
        return $result;
    }
 
    /**
     * @param $name
     * @return string
     */
    private function generateURL($name): string
    {
        $url = preg_replace('#[^\da-z]+#i', '-', $name);
        $url = strtolower($url);
        return $this->generateNewUrl($url);
    }

    /**
     * @param string $url
     * @return string
     */
    private function generateNewUrl(string $url): string
    {
        $randomString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 5);
        $rand = rand(100, 999);
        return $url . '-' . 'as' . $rand  . $randomString;
    }

}
