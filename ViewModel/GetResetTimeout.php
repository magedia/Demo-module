<?php

declare(strict_types=1);

namespace Magedia\Demo\ViewModel;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magedia\Demo\Model\ResetData\ResourceModel\LastResetTime as LastResetResource;
use Magedia\Demo\Model\ResetData\LastResetTime;
use Magedia\Demo\Api\CronMetadataInterface;

class GetResetTimeout implements ArgumentInterface
{
    /**
     * @var LastResetResource
     */
    private LastResetResource $lastResetTimeResource;

    /**
     * @var LastResetTime
     */
    private LastResetTime $lastResetTime;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param LastResetResource $lastResetTimeResource
     * @param LastResetTime $lastResetTime
     * @param SerializerInterface $serializer
     */
    public function __construct(
        LastResetResource $lastResetTimeResource,
        LastResetTime $lastResetTime,
        SerializerInterface $serializer
    ) {
        $this->lastResetTimeResource = $lastResetTimeResource;
        $this->lastResetTime = $lastResetTime;
        $this->serializer = $serializer;
    }

    /**
     * Get settings for reset timer
     *
     * @return string
     */
    public function getTimerSettings(): string
    {
        $this->lastResetTimeResource->load($this->lastResetTime, 1);
        $lastReset = $this->lastResetTime->getData('updated_at');

        return $this->serializer->serialize([
            'last_reset_time' => $lastReset,
            'reset_timeout' => CronMetadataInterface::CRON_RESET_TIMEOUT
        ]);
    }
}
