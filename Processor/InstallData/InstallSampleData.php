<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\InstallData;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Setup\Module\DataSetup;
use Magento\Framework\ObjectManagerInterface;
use Magedia\Demo\Processor\InstallData\DemoInstallContext;
use Magedia\Demo\Processor\InstallData\InstalledModules;

class InstallSampleData
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var DataSetup
     */
    private DataSetup $dataSetup;

    /**
     * @var DemoInstallContext
     */
    private DemoInstallContext $demoInstallContext;

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * @var InstalledModules
     */
    private InstalledModules $installedModules;

    /**
     * @param DataSetup $dataSetup
     * @param DemoInstallContext $demoInstallContext
     * @param LoggerInterface $logger
     * @param InstalledModules $installedModules
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        DataSetup $dataSetup,
        DemoInstallContext $demoInstallContext,
        LoggerInterface $logger,
        InstalledModules $installedModules,
        ObjectManagerInterface $objectManager
    ) {
        $this->logger = $logger;
        $this->dataSetup = $dataSetup;
        $this->demoInstallContext = $demoInstallContext;
        $this->objectManager = $objectManager;
        $this->installedModules = $installedModules;
    }

    /**
     * Install sample data in all modules
     *
     * @return void
     */
    public function setUpData(): void
    {
        foreach ($this->installedModules->createInstallDataPath() as $moduleDataPath){
            try {
                $installData = $this->objectManager->get($moduleDataPath);
                $installData->install($this->dataSetup, $this->demoInstallContext);
                $this->logger->info("Successfully install Sample Data for $moduleDataPath module.");
            }
            catch(Exception $e) {
                $this->logger->info($e->getMessage());
                continue;
            }
        }
    }
}
