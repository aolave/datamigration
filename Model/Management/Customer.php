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
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Serialize\Serializer\Json as JsonSerialize;
use Omnipro\DataMigration\Logger\Logger;
use Omnipro\DataMigration\Model\Management\Attributes\Cleaner;
use Omnipro\DataMigration\Model\Management\Attributes\CustomAttributeConverter;
use Omnipro\DataMigration\Model\Status;

/**
 * This Customer class
 *
 * @package Omnipro\DataMigration\Model\Management
 * @class Customer
 * @version  1.0.3
 */
class Customer
{
    /**
     * Construct
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerInterfaceFactory $customerFactory
     * @param JsonSerialize $jsonSerialize
     * @param Logger $logger
     */
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private CustomerInterfaceFactory $customerFactory,
        private JsonSerialize $jsonSerialize,
        private Logger $logger
    ) {
    }

    /**
     * Create customer
     *
     * @param array $customerData
     * @return string
     */
    public function create(array $customerData): string
    {
        $result = Status::FAILURE;

        if ($this->getCustomer($customerData['email'])) {
            return Status::EXISTS;
        }

        try
        {
            $customAttributes = CustomAttributeConverter::convert(
                $this->jsonSerialize->unserialize($customerData['custom_attributes'])
            );
            $customerData = Cleaner::clean($customerData);
            $customer = $this->customerFactory->create([
                'data' => $customerData
            ]);
            foreach ($customAttributes as $attributeCode => $attributeValue) {
                $customer->setCustomAttribute($attributeCode, $attributeValue);
            }
            $this->customerRepository->save($customer);
            $result = Status::SUCCESS;
        } catch (Exception $e) {
            $this->logger->info( $e->getMessage());
        }

        return $result;
    }

    /**
     * Get customer
     *
     * @param string $email
     * @return CustomerInterface|null
     */
    private function getCustomer(string $email): ?CustomerInterface
    {
        try {
            return $this->customerRepository->get($email);
        } catch (Exception $e) {
            return null;
        }
    }
}
