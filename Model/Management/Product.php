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
use Magento\Framework\Api\CustomAttributesDataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json as JsonSerialize;
use Magento\Tax\Model\TaxRuleRepository;
use Omnipro\DataMigration\Logger\Logger;
use Omnipro\DataMigration\Model\Status;
use Omnipro\DataMigration\Model\Management\Common\CustomAttributeConverter;
use Omnipro\DataMigration\Model\Management\Product\Attributes\Equivalences;

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
     * @param TaxRuleRepository $taxRuleRepository
     */
    public function __construct(
        private CategoryLinkManagementInterface $categoryLinkManagement,
        private ProductFactory $productFactory,
        private ProductRepositoryInterface $productRepository,
        private JsonSerialize $jsonSerialize,
        private Logger $logger,
        private TaxRuleRepository $taxRuleRepository
    ) {
    }

     /**
     * @param array $item
     * @return string
     * @throws NoSuchEntityException
     */
    public function managerProduct(array $item)
    {
        $result = Status::FAILURE;
        try {

            if( $this->productRepository->get($item['sku'])) {
                return Status::EXISTS;
            }

        } catch (NoSuchEntityException $e) {
            $result = $this->create($item);
        } catch (LocalizedException $e) {
            $this->logger->error("An error has occurred with SKU {$item['sku']}: " . $e->getMessage());
        }

        return $result;
    }

    /**
     * Create product
     *
     * @param array $item
     * @return string
     */
    public function create(array $item): string
    {
        $customAttributes = CustomAttributeConverter::convert(
            $this->jsonSerialize->unserialize($item[CustomAttributesDataInterface::CUSTOM_ATTRIBUTES]),
            Equivalences::GET
        );
        $attributes = CustomAttributeConverter::convertNativeAttributes(
            $this->jsonSerialize->unserialize($item[CustomAttributesDataInterface::CUSTOM_ATTRIBUTES]),
            Equivalences::GET               
        );
        $item[Equivalences::CODE_ATTRIBUTESET] = Equivalences::OPTIONS[Equivalences::CODE_ATTRIBUTESET][(int)$item[Equivalences::CODE_ATTRIBUTESET]] ?? 4;
        $urlKey = $this->generateURL($attributes['name']);

        $newProduct = $this->productFactory->create();
        $newProduct->setSku($item['sku']);
        $newProduct->setName($attributes['name']);
        $newProduct->setAttributeSetId($item[Equivalences::CODE_ATTRIBUTESET]);
        $newProduct->setVisibility($attributes['visibility']);
        $newProduct->setTaxClassId($attributes['tax_class_id']);
        $newProduct->setStatus($attributes['status']);
        $newProduct->setPrice($attributes['price']);
        // $newProduct->setQty(0);
        $newProduct->setTypeId($item['type_id']);
        $newProduct->setDescription($attributes['description']);
        $newProduct->setShortDescription($attributes['short_description']);
        $newProduct->setCustomAttributes($customAttributes);
        $newProduct->setUrlKey($urlKey);

        $this->productRepository->save($newProduct);

        return Status::SUCCESS;
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
