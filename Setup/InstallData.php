<?php
/**
 * @author    Magedia Team
 * @copyright Copyright (c) 2021 Magedia (https://www.magedia.com)
 * @package   Magedia_PdfInvoice
 * @version   1.0.0
 */

namespace Magedia\Demo\Setup;

use DateInterval;
use DateTime;
use Exception;
use Magedia\Demo\Model\ResetData\LastResetTimeFactory;
use Magedia\Demo\Api\CronMetadataInterface;
use Magedia\Demo\Model\ResetData\ResourceModel\LastResetTime as LastResetResource;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var LastResetTimeFactory
     */
    private LastResetTimeFactory $lastResetTimeFactory;

    /**
     * @var LastResetResource
     */
    private LastResetResource $lastResetResource;

    /**
     * @var TimezoneInterface
     */
    private TimezoneInterface $timezone;

    /**
     * @param LoggerInterface $logger
     * @param LastResetTimeFactory $lastResetTimeFactory
     * @param LastResetResource $lastResetResource
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        LoggerInterface $logger,
        LastResetTimeFactory $lastResetTimeFactory,
        LastResetResource $lastResetResource,
        TimezoneInterface $timezone
    ) {
        $this->logger = $logger;
        $this->lastResetTimeFactory = $lastResetTimeFactory;
        $this->lastResetResource = $lastResetResource;
        $this->timezone = $timezone;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws AlreadyExistsException
     * @throws LocalizedException
     * @throws Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $installDemoTime = $this->lastResetTimeFactory->create();
        $updatedAt = $this->timezone->convertConfigTimeToUtc($this->timezone->date());
        $nextUpdate = new DateTime("$updatedAt");
        $nextUpdate->add(new DateInterval('PT' . CronMetadataInterface::CRON_RESET_TIMEOUT . 'M'));
        $installDemoTime->addData(['updated_at' => $updatedAt, 'next_update' => $nextUpdate]);
        $this->lastResetResource->save($installDemoTime);

        $setup->endSetup();
    }
}
