<?php

declare(strict_types=1);

namespace Magedia\Demo\Controller\Timer;

use Magedia\Demo\Api\Data\CronMetadataInterface;
use Magedia\Demo\Model\LastResetTime;
use Magedia\Demo\Model\ResourceModel\LastResetTime as LastResetResource;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;

class Config extends Action
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
     * @var JsonFactory
     */
    private JsonFactory $jsonFactory;

    /**
     * @param Context $context
     * @param LastResetResource $lastResetTimeResource
     * @param LastResetTime $lastResetTime
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        LastResetResource $lastResetTimeResource,
        LastResetTime $lastResetTime,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->lastResetTimeResource = $lastResetTimeResource;
        $this->lastResetTime = $lastResetTime;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $this->lastResetTimeResource->load($this->lastResetTime, 1);
        $lastReset = $this->lastResetTime->getData('updated_at');

        return $this->jsonFactory->create()->setData([
            'last_reset_time' => $lastReset,
            'reset_timeout' => CronMetadataInterface::CRON_RESET_TIMEOUT
        ]);
    }
}
