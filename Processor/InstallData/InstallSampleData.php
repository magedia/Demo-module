<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\InstallData;

use Exception;
use Magedia\Demo\Processor\MagediaSampleData\ModulesPatches;
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

    public function __construct(
        LoggerInterface $logger,
        ModulesPatches $modulesPatches,
        ObjectManagerInterface $objectManager
    ) {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->modulesPatches = $modulesPatches;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function setUpData(): void
    {
        $this->setupModulesPatches();
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
