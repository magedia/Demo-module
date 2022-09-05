<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\MagediaSampleData;

use Magedia\Demo\Api\UnavailableModulesMetadataInterface;

class ModulesPatches
{
    /**
     * @var string
     */
    private $magentoPath;

    /**
     * Create path to InstallData for all installed modules
     *
     * @return array
     */
    public function createInstallDataPath(): array
    {
        $availableModules = $this->getInstallModulesName();
        foreach ($availableModules as $moduleName){
            $modulePatches = [];
            $pathToPatches = "$this->magentoPath/$moduleName/Setup/Patch/Data";
            if (is_dir($pathToPatches)) {
                $modulePatches = array_slice(scandir($pathToPatches), 2);
            }

            foreach ($modulePatches as $patch) {
                $installDataPath[$moduleName][] = str_replace('.php', '', $patch);
            }
        }

        return $installDataPath ?? [];
    }

    /**
     * Get all installed module names
     *
     * @return array
     */
    public function getInstallModulesName(): array
    {
        $availableModules = [];
        $this->magentoPath = dirname(__FILE__, 4);
        $installModules = array_slice(scandir($this->magentoPath), 2);
        foreach ($installModules as $moduleName) {
            if (!in_array($moduleName, UnavailableModulesMetadataInterface::UNAVAILABLE_MODULES)) {
                $availableModules[] = $moduleName;
            }
        }

        return $availableModules;
    }
}
