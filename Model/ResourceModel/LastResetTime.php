<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\ResourceModel;

use Magedia\Demo\Api\Data\Magedia\ResetMetadataInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class LastResetTime extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResetMetadataInterface::DEMO_RESET_CONFIG_TABLE, 'id');
    }
}
