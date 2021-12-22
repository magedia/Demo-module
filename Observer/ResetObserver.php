<?php

namespace Magedia\Demo\Observer;

use Magedia\Demo\Model\ResetData\Reset\ResetTable;
use Magedia\Demo\Processor\InstallData\InstallSampleData;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ResetObserver implements ObserverInterface
{
    private ResetTable $resetTable;
    private InstallSampleData $installSampleData;

    public function __construct(
        ResetTable $resetTable,
        InstallSampleData $installSampleData
    ) {
        $this->resetTable = $resetTable;
        $this->installSampleData = $installSampleData;
    }

    public function execute(Observer $observer)
    {
        $this->resetTable->truncateTables();
        $this->installSampleData->setUpData();
    }
}
