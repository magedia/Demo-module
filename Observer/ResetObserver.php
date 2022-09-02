<?php

namespace Magedia\Demo\Observer;

use Magedia\Demo\Model\Reset\DataRemover;
use Magedia\Demo\Processor\InstallData\InstallSampleData;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magedia\Demo\Model\Reset\MagentoTables\Order\SalesTables;
use Magedia\Demo\Model\Reset\MagediaTables\CustomTables;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RuntimeException;

class ResetObserver implements ObserverInterface
{
    private DataRemover $dataRemover;
    private InstallSampleData $installSampleData;
    private array $tableToRemove = [];

    /**
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function __construct(
        DataRemover $dataRemover,
        InstallSampleData $installSampleData,
        SalesTables $salesTables,
        CustomTables $customTables
    ) {
        $this->dataRemover = $dataRemover;
        $this->installSampleData = $installSampleData;
        $this->tableToRemove[] = array_merge(
            $salesTables->getSalesOrderTables(),
            $customTables->getCustomTableNames()
        );
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
    }
}
