<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\ResetData\Reset;

use Magedia\Demo\Api\Data\ResetMetadataInterface;
use Magedia\Demo\Model\ResetData\Reset\CustomTables;
use Magedia\Demo\Model\ResetData\Reset\SetResetTime;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RuntimeException;

class ResetTable
{
    /**
     * @var CustomTables
     */
    private CustomTables $customTables;

    /**
     * @var SetResetTime
     */
    private SetResetTime $setResetTime;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param CustomTables $customTables
     * @param SetResetTime $setResetTime
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        CustomTables $customTables,
        SetResetTime $setResetTime,
        ResourceConnection $resourceConnection
    ) {
        $this->customTables = $customTables;
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
    public function truncateTables()
    {
        $connection =  $this->resourceConnection->getConnection('write');
        $customTables = $this->customTables->getCustomTableNames();
        foreach ($customTables as $table){
            $connection->delete($table['TABLE_NAME']);
        }
        $this->setResetTime->setLastResetTime();
    }
}
