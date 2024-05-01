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

namespace Omnipro\DataMigration\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This sync products command
 *
 * @class SyncProductsCommand
 * @package Omnipro\DataMigration\Console\Command
 * @since version 1.0.3
 */
class SyncProductsCommand extends Command
{
    public const NAME = 'omnipro:sync:products';

    /**
     * Construct
     *
     * @param State $state
     * @param string|null $name
     */
    public function __construct(
        protected State $state,
        string $name = null
    )
    {
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     * @throws LocalizedException
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->state->setAreaCode(Area::AREA_CRONTAB);
        return Command::SUCCESS;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName(self::NAME);
        $this->setDescription("Sync products to Magento");
        parent::configure();
    }
}
