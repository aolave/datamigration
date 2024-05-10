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
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Api\CustomAttributesDataInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json as JsonSerialize;
use Omnipro\DataMigration\Logger\Logger;
use Omnipro\DataMigration\Model\Management\Common\Cleaner;
use Omnipro\DataMigration\Model\Management\Common\CustomAttributeConverter;
use Omnipro\DataMigration\Model\Management\Customer\Attributes\CleanFieldList;
use Omnipro\DataMigration\Model\Management\Customer\Attributes\Equivalences;
use Omnipro\DataMigration\Model\Status;
use Magento\Framework\DB\TransactionFactory;

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
     * @param AttributeSetRepositoryInterface $attributeSetFactory
     * @param AttributeRepositoryInterface $attributeRepository
     * @param ResourceConnection $resourceConnection
     * @param JsonSerialize $jsonSerialize
     * @param TransactionFactory $transactionFactory
     * @param Logger $logger
     */
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private CustomerInterfaceFactory $customerFactory,
        protected AttributeSetRepositoryInterface $attributeSetRepository,
        protected AttributeRepositoryInterface $attributeRepository,
        private ResourceConnection $resourceConnection,
        private JsonSerialize $jsonSerialize,
        protected  TransactionFactory $transactionFactory,
        private Logger $logger
    ) {
    }

    /**
     * Create customer
     *
     * @param array $dataCustomers
     * @param array $dataAttributes
     * @return string
     */
    public function createMultiple(array $dataCustomers, array $dataAttributes): string
    {
        $result = Status::FAILURE;
        if(empty($dataCustomers)){
            return $result;
        }

        $resource = $this->resourceConnection->getConnection();

        try {
            $tableName = $resource->getTableName('customer_entity');
            // $adapter->insert($tableName, $customerData);
            $insert = $resource->insertMultiple($tableName, $dataCustomers);

            if($insert){
                $this->createCustomAttributes($dataAttributes);
                $result = Status::SUCCESS;
            }

        } catch (Exception $e) {
            $this->logger->info("Error processing bulk Insert customer: " . $e->getMessage());
        }

        return $result;
    }

    /**
     * @param array $dataAttributes
     * @return void
     */
    public function createCustomAttributes(array $dataAttributes): void
    {
        foreach ($dataAttributes as $dataAttribute) {
            try {
                $customerId = $dataAttribute['entity_id'];

                $customAttributes = CustomAttributeConverter::convert(
                    $this->jsonSerialize->unserialize($dataAttribute[CustomAttributesDataInterface::CUSTOM_ATTRIBUTES]),
                    Equivalences::GET
                );

                foreach ($customAttributes as $attributeCode => $attributeValue) {
                    $attributeId = $this->getAttributeIdByCode($attributeCode);
                    $this->insertAttributeValue($customerId, $attributeId, $attributeValue);
                }

            } catch (NoSuchEntityException $e) {
                $this->logger->info("Customer error id: " . $customerId);
            } catch (Exception $e) {
                $this->logger->info("Error processing customer attributes: " . $e->getMessage());
            }
        }
    }

    /**
     * @param string $attributeCode
     * @return int|string
     * @throws NoSuchEntityException
     */
    protected function getAttributeIdByCode(string $attributeCode): int|string
    {
        $attribute = $this->attributeRepository->get('customer', $attributeCode);

        return $attribute->getAttributeId();
    }

    /**
     * @param int|string $customerId
     * @param int|string $attributeId
     * @param int|string$value
     * @return void
     */
    protected function insertAttributeValue(
        int|string $customerId,
        int|string $attributeId,
        int|string $value): void
    {
        $attributeTable = $this->getCustomerAttributeTable($attributeId);

        $this->resourceConnection->getConnection()->insert($attributeTable, [
             'entity_id' => $customerId,
             'attribute_id' => $attributeId,
             'value' => $value
         ]);
    }

    /**
     * @param int|string $attributeId
     * @return string|null
     */
    protected function getCustomerAttributeTable(int|string $attributeId): ?string
    {
        try {
            $attribute = $this->attributeRepository->get('customer', $attributeId);
            $attributeSetId = $attribute->getBackendType();

            return 'customer_entity_' . $attributeSetId;
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Function check customer
     *
     * @param array $customerData
     * @return bool
     */
    public function checkCustomer(array $customerData): bool
    {
        if ($this->getCustomer($customerData[CustomerInterface::EMAIL])) {
            return true;
        }

        return false;
    }

    /**
     * Function prepareCustomerData
     *
     * @param $customerRow
     * @return array
     */
    public function prepareCustomerData($customerRow): array
    {
        try {
        /*
        $customAttributes = CustomAttributeConverter::convert(
            $this->jsonSerialize->unserialize($customerRow[CustomAttributesDataInterface::CUSTOM_ATTRIBUTES]),
            Equivalences::GET
        );
        */

        $customerData = Cleaner::clean($customerRow, CleanFieldList::GET);
        $customerData = $this->convertRequiredFields($customerData);

        $customerData[Equivalences::CODE_GENDER] = Equivalences::OPTIONS[Equivalences::CODE_GENDER][(int)$customerData[Equivalences::CODE_GENDER]]?? 7;
        $customerData[Equivalences::CODE_GROUPID] = Equivalences::OPTIONS[Equivalences::CODE_GROUPID][(int)$customerData[Equivalences::CODE_GROUPID]]?? 0;

        } catch (Exception $e) {
            $customerData = $customerRow;
            $this->logger->info("Error processing customer save: " . $e->getMessage());
        }

        return $customerData;
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
        } catch (Exception) {
            return null;
        }
    }

    /**
     * Get value for required fields
     *
     * @param array $customerData
     * @return array
     */
    private function convertRequiredFields(array $customerData): array
    {
        if (empty($customerData['firstname'])) {
            $customerData['firstname'] = 'First name';
        }
        if (empty($customerData['lastname'])) {
            $customerData['lastname'] = 'Last name';
        }

        return $customerData;
    }
}
