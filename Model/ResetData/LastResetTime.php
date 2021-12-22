<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\ResetData;

use Magento\Framework\Model\AbstractModel;
use Magedia\Demo\Model\ResetData\ResourceModel\LastResetTime as LastResetResource;

class LastResetTime extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(LastResetResource::class);
    }
}
