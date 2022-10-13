<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\Reset;

use Magedia\Demo\Api\Data\Magedia\ConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class ConfigUpdater
{
    /**
     * @var WriterInterface
     */
    private WriterInterface $configWriter;

    /**
     * @param WriterInterface $configWriter
     */
    public function __construct(WriterInterface $configWriter)
    {
        $this->configWriter = $configWriter;
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        foreach (ConfigInterface::PDF_INVOICE_CONFIG as $path => $value) {
            $this->configWriter->save($path, $value);
        }
    }
}
