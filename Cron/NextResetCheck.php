<?php

namespace Magedia\Demo\Cron;

use DateInterval;
use DateTime;
use Exception;
use Magedia\Demo\Api\CronMetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magedia\Demo\Model\ResetData\LastResetTimeFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;

class NextResetCheck
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    private LastResetTimeFactory $lastResetTimeFactory;
    private TimezoneInterface $timezone;
    private EventManager $eventManager;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger,
        LastResetTimeFactory $lastResetTimeFactory,
        TimezoneInterface $timezone,
        EventManager $eventManager
    ) {
        $this->logger = $logger;
        $this->lastResetTimeFactory = $lastResetTimeFactory;
        $this->timezone = $timezone;
        $this->eventManager = $eventManager;
    }

    /**
     * Reset time check
     *
     * @throws LocalizedException
     * @throws Exception
     */
    public function execute()
    {
        $lastReset = $this->lastResetTimeFactory->create();
        $resetTimeModel = $lastReset->load(1);
        $nextReset = $resetTimeModel->getData('next_update');
        $currentTime = $this->timezone->convertConfigTimeToUtc($this->timezone->date());
        $currentTimeString = new DateTime("$currentTime");
        $currentTimeString->add(new DateInterval('PT' . 1 . 'M'));
        if($currentTimeString >= new DateTime("$nextReset")) {
            $this->eventManager->dispatch('reset_sample_data');
        }
    }
}
