<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\InstallData;

use Magento\Framework\Setup\ModuleContextInterface;

class DemoInstallContext implements ModuleContextInterface
{
    /**
     * Get module version for install sample data
     *
     * @return string
     */
    public function getVersion(): string
    {
        return '';
    }
}
