<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\InstallData;

use Magedia\Demo\Api\UnavailableModulesMetadataInterface;

class InstalledModules
{
    private array $installDataPath;
    private array $availableModule = [];

    /**
     * Create path to InstallData for all installed modules
     *
     * @return array
     */
    public function createInstallDataPath(): array
    {
        $this->getInstallModulesName();
        foreach ($this->availableModule as $moduleName){
            $this->installDataPath[$moduleName] = "\Magedia\\$moduleName\Setup\InstallData";
        }

        return $this->installDataPath;
    }

    /**
     * Get all installed module names
     *
     * @return array
     */
    public function getInstallModulesName(): array
    {
        $path = dirname(__FILE__, 4);
        $installModules = array_slice(scandir($path), 2);
        foreach ($installModules as $moduleName) {
            if (!in_array($moduleName, UnavailableModulesMetadataInterface::UNAVAILABLE_MODULES)) {
                array_push($this->availableModule, $moduleName);
            }
        }

        return $this->availableModule;
    }
}
