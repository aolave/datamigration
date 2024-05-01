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

namespace Omnipro\DataMigration\Model\Management;

use Exception;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\CustomAttributesDataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json as JsonSerialize;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Omnipro\DataMigration\Logger\Logger;
use Omnipro\DataMigration\Model\Management\Common\Cleaner;
use Omnipro\DataMigration\Model\Management\Common\CustomAttributeConverter;
use Omnipro\DataMigration\Model\Management\CustomerAddress\Attributes\CleanFieldList;
use Omnipro\DataMigration\Model\Management\CustomerAddress\Attributes\Equivalences;
use Omnipro\DataMigration\Model\Status;

/**
 * This Customer class
 *
 * @package Omnipro\DataMigration\Model\Management
 * @class Customer
 * @version  1.0.3
 */
class CustomerAddress
{
    private array $customerAddress;

    /**
     * Construct
     *
     * @param AddressRepositoryInterface $addressRepository
     * @param AddressInterfaceFactory $addressFactory
     * @param JsonSerialize $jsonSerialize
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param Logger $logger
     */
    public function __construct(
        private AddressRepositoryInterface $addressRepository,
        private AddressInterfaceFactory $addressFactory,
        private JsonSerialize $jsonSerialize,
        private SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        private Logger $logger
    ) {
    }

    /**
     * Create customer address bulk
     *
     * @param CustomerInterface $customer
     * @param array $customerAddressRows
     * @param Status $status
     * @return void
     */
    public function bulkCreate(
        CustomerInterface $customer,
        array $customerAddressRows,
        Status $status
    ): void {
        if (count($customerAddressRows) === 0) {
            return;
        }
        $this->customerAddress = [];
        foreach ($customerAddressRows as $customerAddressRow) {
            $customerAddressRow['parent_id'] = $customer->getId();
            $result = $this->create($customer, $customerAddressRow);
            $status->increment($result);
        }
        $customer->setAddresses($this->customerAddress);
    }

    /**
     * Create customer address
     *
     * @param CustomerInterface $customer
     * @param array $customerAddressData
     * @return string
     */
    public function create( CustomerInterface $customer, array $customerAddressData): string
    {
        $result = Status::FAILURE;
        try
        {
            if ($address = $this->getAddress($customerAddressData)) {
                $this->customerAddress[] = $address;
                return Status::EXISTS;
            }
            $customAttributes = CustomAttributeConverter::convert(
                $this->jsonSerialize->unserialize($customerAddressData[CustomAttributesDataInterface::CUSTOM_ATTRIBUTES]),
                Equivalences::GET
            );
            $customerAddressData = Cleaner::clean($customerAddressData, CleanFieldList::GET);
            $address = $this->addressFactory->create([
                'data' => $customerAddressData
            ]);
            $address->setCustomerId($customer->getId());
            // TODO: VALID IF REGION IS EQUALS
            //$address->setCountryId('CL');
            foreach ($customAttributes as $attributeCode => $attributeValue) {
                $address->setCustomAttribute($attributeCode, $attributeValue);
            }
            $this->addressRepository->save($address);
            $this->customerAddress[] = $address;
            $result = Status::SUCCESS;
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }

        return $result;
    }

    /**
     * Get address
     *
     * @param array $address
     * @return AddressInterface|null
     * @throws LocalizedException
     */
    public function getAddress(
       array $address
    ): ?AddressInterface {
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder
            ->addFilter('parent_id', $address['parent_id'])
            ->addFilter(AddressInterface::COUNTRY_ID, $address[AddressInterface::COUNTRY_ID])
            ->addFilter(AddressInterface::STREET, $address[AddressInterface::STREET])
            ->addFilter(AddressInterface::REGION, $address[AddressInterface::REGION])
            ->addFilter(AddressInterface::REGION_ID, $address[AddressInterface::REGION_ID])
            ->addFilter(AddressInterface::CITY, $address[AddressInterface::CITY])
            ->addFilter(AddressInterface::POSTCODE, $address[AddressInterface::POSTCODE])
            ->create();

        $addresses = $this->addressRepository->getList($searchCriteria)->getItems();

        return $addresses ? reset($addresses) : null;
    }
}
