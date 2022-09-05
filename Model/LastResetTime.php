<?php

declare(strict_types=1);

namespace Magedia\Demo\Model;

use Magedia\Demo\Model\ResourceModel\LastResetTime as LastResetResource;
use Magento\Framework\Model\AbstractModel;

class LastResetTime extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(LastResetResource::class);
    }
}
