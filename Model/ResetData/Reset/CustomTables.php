<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\ResetData\Reset;

use Magedia\Demo\Api\Data\ResetMetadataInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;

class CustomTables
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var DeploymentConfig
     */
    private DeploymentConfig $deploymentConfig;

    /**
     * @var string
     */
    private string $resetConfigTable;

    /**
     * @param ResourceConnection $resourceConnection
     * @param DeploymentConfig $deploymentConfig
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        DeploymentConfig $deploymentConfig
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->deploymentConfig = $deploymentConfig;
        $this->resetConfigTable = ResetMetadataInterface::DEMO_RESET_CONFIG_TABLE;
    }

    /**
     * Get all custom table name for all installed modules
     *
     * @return array
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function getCustomTableNames(): array
    {
        $connection =  $this->resourceConnection->getConnection('read');
        $select = $connection->select()
            ->from("information_schema.tables")
            ->columns(['table_name'])
            ->where('table_schema = ?', $this->deploymentConfig->get('db/connection/default/dbname'))
            ->where("table_name not like ?", "%$this->resetConfigTable%")
            ->where('table_name like ?', ResetMetadataInterface::CUSTOM_TABLE_LIKE);

        return $connection->fetchAll($select);
    }
}
