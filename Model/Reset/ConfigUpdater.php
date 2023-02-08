<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\Reset;

use Magedia\Demo\Api\Data\Magedia\AcartConfigInterface;
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class ConfigUpdater
{
    /**
     * @var WriterInterface
     */
    private WriterInterface $configWriter;

    /**
     * @var Pool
     */
    private Pool $cacheFrontendPool;

    /**
     * @var TypeListInterface
     */
    private TypeListInterface $cacheTypeList;

    /**
     * @param WriterInterface $configWriter
     * @param TypeListInterface $cacheTypeList
     * @param Pool $cacheFrontendPool
     */
    public function __construct(
        WriterInterface $configWriter,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ) {
        $this->configWriter = $configWriter;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        foreach (AcartConfigInterface::ACART_CONFIG as $path => $value) {
            $this->configWriter->save($path, $value);
        }

        $this->flushCache();
    }

    /**
     * @return void
     */
    private function flushCache(): void
    {
        $types = [
            'config',
            'layout',
            'block_html',
            'collections',
            'reflection',
            'db_ddl',
            'eav',
            'config_integration',
            'config_integration_api',
            'full_page',
            'translate',
            'config_webservice'
        ];

        foreach ($types as $type) {
            $this->cacheTypeList->cleanType($type);
        }

        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
