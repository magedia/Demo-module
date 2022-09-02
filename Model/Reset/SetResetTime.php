<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\Reset;

use DateInterval;
use DateTime;
use Magedia\Demo\Api\CronMetadataInterface;
use Magedia\Demo\Model\LastResetTimeFactory;
use Magedia\Demo\Model\ResourceModel\LastResetTime as LastResetResource;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class SetResetTime
{
    /**
     * @var LastResetTimeFactory
     */
    private LastResetTimeFactory $lastResetTime;

    /**
     * @var LastResetResource
     */
    private LastResetResource $lastResetResource;

    /**
     * @var TimezoneInterface
     */
    private TimezoneInterface $timezone;

    /**
     * @param LastResetTimeFactory $lastResetTime
     * @param LastResetResource $lastResetResource
     */
    public function __construct(
        LastResetTimeFactory $lastResetTime,
        LastResetResource $lastResetResource,
        TimezoneInterface $timezone
    ){
        $this->lastResetTime = $lastResetTime;
        $this->lastResetResource = $lastResetResource;
        $this->timezone = $timezone;
    }

    /**
     * Insert into demo table current and next reset time
     *
     * @throws AlreadyExistsException
     * @throws LocalizedException
     */
    public function setLastResetTime()
    {
        $lastReset = $this->lastResetTime->create();
        $lastResetUpdate = $lastReset->load(1);
        $updatedAt = $this->timezone->convertConfigTimeToUtc($this->timezone->date());
        $nextUpdate = new DateTime("$updatedAt");
        $nextUpdate->add(new DateInterval('PT' . CronMetadataInterface::CRON_RESET_TIMEOUT . 'M'));
        $lastResetUpdate->addData(['updated_at' => $updatedAt, 'next_update' => $nextUpdate]);
        $this->lastResetResource->save($lastResetUpdate);
    }
}
