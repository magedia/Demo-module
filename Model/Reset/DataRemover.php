<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\Reset;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RuntimeException;

class DataRemover
{
    /**
     * @var SetResetTime
     */
    private SetResetTime $setResetTime;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param SetResetTime $setResetTime
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        SetResetTime $setResetTime,
        ResourceConnection $resourceConnection
    ) {
        $this->setResetTime = $setResetTime;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Delete data from all custom tables
     *
     * @throws FileSystemException
     * @throws RuntimeException
     * @throws AlreadyExistsException
     * @throws LocalizedException
     */
    public function truncateTables(array $tables)
    {
        $connection =  $this->resourceConnection->getConnection('write');
        foreach ($tables[0] as $table){
            $connection->delete($table['TABLE_NAME']);
        }
        $this->setResetTime->setLastResetTime();
    }
}
