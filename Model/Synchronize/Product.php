<?php
/**
 * Copyright © 2024 - Omnipro (https://www.omni.pro/)
 *
 * @author      Karen  Ceballos <karen.ceballos@omni.pro>
 * @date        30/04/2024, 16:00
 * @category    Omnipro
 * @module      Omnipro/DataMigration
 */

 declare(strict_types=1);

namespace Omnipro\DataMigration\Model\Synchronize;

use Omnipro\DataMigration\Model\ProductRepository as OldProductRepository;
use Omnipro\DataMigration\Model\Management\Product as ProductManagement;
use Omnipro\DataMigration\Model\Status;

/**
 * This product class
 *
 * @package Omnipro\DataMigration\Model\Synchronize
 * @class Product
 * @since 1.0.3
 */
class Product
{
    const PAGE_SIZE = 500;
    const PAGE_INIT = 1;

    /**
     * Construct
     *
     * @param OldProductRepository $oldProductRepository
     * @param ProductManagement $productManagement
     * @param Status $status
     */
    public function __construct(
        private OldProductRepository $oldProductRepository,
        private ProductManagement $productManagement,
        private Status $status
    ){
    }

     /**
     * Main process
     *
     * @return Status
     */
    public function process(): Status
    {
        $total = $this->oldProductRepository->count();
        $totalPages = ceil($total / self::PAGE_SIZE);
        $page = self::PAGE_INIT;
        $this->status->setTotal($total);
        while ($page <= $totalPages) {
            $customerRows = $this->oldProductRepository->getList($page, self::PAGE_SIZE);
            foreach ($customerRows as $customerRow) {
                $result = $this->productManagement->create($customerRow);
                $this->status->increment($result);
            }
            $page++;
        }

        return $this->status;
    }

}
