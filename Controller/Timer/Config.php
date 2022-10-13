<?php
/**
 * @author    Magedia Team
 * @copyright Copyright (c) 2021 Magedia (https://www.magedia.com)
 * @package   Magedia_PdfInvoice
 * @version   1.0.0
 */
namespace Magedia\Demo\Controller\Timer;

use Magedia\Demo\Api\CronMetadataInterface;
use Magedia\Demo\Model\LastResetTime;
use Magedia\Demo\Model\ResourceModel\LastResetTime as LastResetResource;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Config extends Action
{
    private LastResetResource $lastResetTimeResource;
    private LastResetTime $lastResetTime;
    private SerializerInterface $serializer;
    private JsonFactory $jsonFactory;

    public function __construct(
        Context $context,
        LastResetResource $lastResetTimeResource,
        LastResetTime $lastResetTime,
        SerializerInterface $serializer,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->lastResetTimeResource = $lastResetTimeResource;
        $this->lastResetTime = $lastResetTime;
        $this->serializer = $serializer;
        $this->jsonFactory = $jsonFactory;
    }

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
