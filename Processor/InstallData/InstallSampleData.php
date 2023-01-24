<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\InstallData;

use Exception;
use Magedia\Demo\Processor\MagediaSampleData\ModulesPatches;
<<<<<<< HEAD
=======
use Magedia\Demo\Processor\MagentoSampleData\Invoice\InvoicesData as MagentoInvoices;
use Magedia\Demo\Processor\MagentoSampleData\CreditMemo\CreditMemosData as MagentoCreditMemos;
use Magedia\Demo\Processor\MagentoSampleData\Shipment\ShipmentsData as MagentoShipments;
use Magedia\Demo\Processor\MagentoSampleData\Order\OrdersData as MagentoOrders;
>>>>>>> main
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;

class InstallSampleData
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * @var ModulesPatches
     */
    private ModulesPatches $modulesPatches;

<<<<<<< HEAD
    public function __construct(
        LoggerInterface $logger,
        ModulesPatches $modulesPatches,
        ObjectManagerInterface $objectManager
=======
    /**
     * @var MagentoOrders
     */
    private MagentoOrders $magentoOrders;
    private MagentoInvoices $magentoInvoices;
    private MagentoCreditMemos $magentoCreditMemos;
    private MagentoShipments $magentoShipments;

    public function __construct(
        LoggerInterface $logger,
        ModulesPatches $modulesPatches,
        ObjectManagerInterface $objectManager,
        MagentoOrders $magentoOrders,
        MagentoInvoices $magentoInvoices,
        MagentoCreditMemos $magentoCreditMemos,
        MagentoShipments $magentoShipments
>>>>>>> main
    ) {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->modulesPatches = $modulesPatches;
<<<<<<< HEAD
=======
        $this->magentoOrders = $magentoOrders;
        $this->magentoInvoices = $magentoInvoices;
        $this->magentoCreditMemos = $magentoCreditMemos;
        $this->magentoShipments = $magentoShipments;
>>>>>>> main
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function setUpData(): void
    {
        $this->setupModulesPatches();
<<<<<<< HEAD
=======
        $this->magentoOrders->createOrders();
        $this->magentoInvoices->createInvoices();
        $this->magentoCreditMemos->createCreditMemos();
        $this->magentoShipments->createShipments();
>>>>>>> main
    }

    /**
     * @return void
     */
    private function setupModulesPatches(): void
    {
        $patches = $this->modulesPatches->createInstallDataPath();
        foreach ($patches as $key => $moduleName) {
            foreach ($moduleName as $patchName) {
                try {
                    $patch = $this->objectManager->get("\Magedia\\$key\Setup\Patch\Data\\$patchName");
                    $patch->apply();
                    $this->logger->info("Successfully install Sample Data for $moduleName module.");
                } catch (Exception $e) {
                    $this->logger->info($e->getMessage());
                    continue;
                }
            }
        }
    }
}
