<?php

declare(strict_types=1);

namespace Magedia\Demo\Observer;

use Magedia\Demo\Model\Reset\ConfigUpdater;
use Magedia\Demo\Model\Reset\DatabaseTables\Magedia\CustomTables;
use Magedia\Demo\Model\Reset\DatabaseTables\Magento\Order\SalesTables;
use Magedia\Demo\Model\Reset\DataRemover;
use Magedia\Demo\Model\Reset\SetResetTime;
use Magedia\Demo\Processor\InstallData\InstallSampleData;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RuntimeException;

class ResetObserver implements ObserverInterface
{
    /**
     * @var DataRemover
     */
    private DataRemover $dataRemover;

    /**
     * @var InstallSampleData
     */
    private InstallSampleData $installSampleData;

    /**
     * @var array
     */
    private array $tableToRemove = [];

    /**
     * @var ConfigUpdater
     */
    private ConfigUpdater $configUpdater;

    /**
     * @var SetResetTime
     */
    private SetResetTime $setResetTime;

    /**
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function __construct(
        DataRemover $dataRemover,
        InstallSampleData $installSampleData,
        SalesTables $salesTables,
        CustomTables $customTables,
        ConfigUpdater $configUpdater,
        SetResetTime $setResetTime
    ) {
        $this->dataRemover = $dataRemover;
        $this->installSampleData = $installSampleData;
        $this->tableToRemove[] = array_merge(
            $salesTables->getSalesOrderTables(),
            $customTables->getCustomTableNames()
        );
        $this->configUpdater = $configUpdater;
        $this->setResetTime = $setResetTime;
    }

    /**
     * @throws FileSystemException
     * @throws RuntimeException
     * @throws AlreadyExistsException
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $this->dataRemover->truncateTables($this->tableToRemove);
        $this->installSampleData->setUpData();
        $this->configUpdater->reset();
        $this->setResetTime->setLastResetTime();
    }
}
