<?php

declare(strict_types=1);

namespace Magedia\Demo\Cron;

use DateInterval;
use DateTime;
use Exception;
use Magedia\Demo\Model\LastResetTimeFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class NextResetCheck
{
    /**
     * @var LastResetTimeFactory
     */
    private LastResetTimeFactory $lastResetTimeFactory;

    /**
     * @var TimezoneInterface
     */
    private TimezoneInterface $timezone;

    /**
     * @var EventManager
     */
    private EventManager $eventManager;

    /**
     * @param LastResetTimeFactory $lastResetTimeFactory
     * @param TimezoneInterface $timezone
     * @param EventManager $eventManager
     */
    public function __construct(
        LastResetTimeFactory $lastResetTimeFactory,
        TimezoneInterface $timezone,
        EventManager $eventManager
    ) {
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
    public function execute(): void
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
