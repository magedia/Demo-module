<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\InstallData;

use Exception;
use Magedia\Demo\Processor\MagediaSampleData\ModulesPatches;
use Magedia\Demo\Processor\MagentoSampleData\Order\OrdersData as MagentoOrders;
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

    /**
     * @var MagentoOrders
     */
    private MagentoOrders $magentoOrders;

    /**
     * @param LoggerInterface $logger
     * @param ModulesPatches $modulesPatches
     * @param ObjectManagerInterface $objectManager
     * @param MagentoOrders $magentoOrders
     */
    public function __construct(
        LoggerInterface $logger,
        ModulesPatches $modulesPatches,
        ObjectManagerInterface $objectManager,
        MagentoOrders $magentoOrders
    ) {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->modulesPatches = $modulesPatches;
        $this->magentoOrders = $magentoOrders;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function setUpData(): void
    {
        $this->setupModulesPatches();
        $this->magentoOrders->createOrders();
    }

    /**
     * @return void
     */
    private function setupModulesPatches(): void
    {
        $patches = $this->modulesPatches->createInstallDataPath();
        foreach ($patches as $key => $moduleName){
            foreach ($moduleName as $patchName) {
                try {
                    $patch = $this->objectManager->get("\Magedia\\$key\Setup\Patch\Data\\$patchName");
                    $patch->apply();
                    $this->logger->info("Successfully install Sample Data for $moduleName module.");
                } catch(Exception $e) {
                    $this->logger->info($e->getMessage());
                    continue;
                }
            }

        }
    }
}
