<?php

declare(strict_types=1);

namespace Magedia\Demo\Observer;

use Magedia\Demo\Model\Reset\DatabaseTables\Magedia\CustomTables;
use Magedia\Demo\Model\Reset\DatabaseTables\Magento\Order\SalesTables;
use Magedia\Demo\Model\Reset\DataRemover;
use Magedia\Demo\Model\Reset\SetResetTime;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RuntimeException;
use Magedia\Demo\Model\Reset\ConfigUpdater;
use Magedia\Demo\Processor\MagediaSampleData\AcartDataInstaller;

class ResetObserver implements ObserverInterface
{
    /**
     * @var DataRemover
     */
    private DataRemover $dataRemover;

    /**
     * @var array
     */
    private array $tableToRemove = [];

    /**
     * @var SetResetTime
     */
    private SetResetTime $setResetTime;

    /**
     * @var ConfigUpdater
     */
    private ConfigUpdater $configUpdater;

    /**
     * @var AcartDataInstaller
     */
    private AcartDataInstaller $acartDataInstaller;

    /**
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function __construct(
        DataRemover $dataRemover,
        SalesTables $salesTables,
        CustomTables $customTables,
        SetResetTime $setResetTime,
        ConfigUpdater $configUpdater,
        AcartDataInstaller $acartDataInstaller
    ) {
        $this->dataRemover = $dataRemover;
        $this->tableToRemove[] = array_merge(
            $salesTables->getSalesOrderTables(),
            $customTables->getCustomTableNames()
        );
        $this->setResetTime = $setResetTime;
        $this->configUpdater = $configUpdater;
        $this->acartDataInstaller = $acartDataInstaller;
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
        $this->configUpdater->reset();
        $this->acartDataInstaller->install();
        $this->setResetTime->setLastResetTime();
    }
}
