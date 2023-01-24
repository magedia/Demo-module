<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\Reset;

use Magento\Framework\App\ResourceConnection;

class DataRemover
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Delete data from all custom tables
     *
     * @param array $tables
     * @return void
     */
    public function truncateTables(array $tables): void
    {
        $connection =  $this->resourceConnection->getConnection('write');
        foreach ($tables[0] as $table) {
            $connection->delete($table['TABLE_NAME']);
        }
    }
}
