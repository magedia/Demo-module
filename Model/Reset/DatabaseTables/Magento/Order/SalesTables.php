<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\Reset\DatabaseTables\Magento\Order;

use Magedia\Demo\Api\Data\Magento\TablesInterface;
use Magedia\Demo\Model\Reset\DatabaseTables\AbstractTableGetter;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;

class SalesTables extends AbstractTableGetter
{
    /**
     * Get all order sales tables
     *
     * @return array
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function getSalesOrderTables(): array
    {
        $connection =  $this->resourceConnection->getConnection('read');
        $select = $connection->select()
            ->from("information_schema.tables")
            ->columns(['table_name'])
            ->where('table_schema = ?', $this->deploymentConfig->get('db/connection/default/dbname'))
            ->where('table_name in( ? )', TablesInterface::SALES_ORDER_TABLES);

        return $connection->fetchAll($select);
    }
}
