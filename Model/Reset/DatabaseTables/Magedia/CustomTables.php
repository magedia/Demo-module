<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\Reset\DatabaseTables\Magedia;

use Magedia\Demo\Api\Data\Magedia\ResetMetadataInterface;
use Magedia\Demo\Model\Reset\DatabaseTables\AbstractTableGetter;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;

class CustomTables extends AbstractTableGetter
{
    /**
     * Get all custom table name for all installed modules
     *
     * @return array
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function getCustomTableNames(): array
    {
        $connection = $this->resourceConnection->getConnection('read');
        $select = $connection->select()
            ->from("information_schema.tables")
            ->columns(['table_name'])
            ->where('table_schema = ?', $this->deploymentConfig->get('db/connection/default/dbname'))
            ->where("table_name not like ?", '%' . ResetMetadataInterface::DEMO_RESET_CONFIG_TABLE . '%')
            ->where('table_name like ?', ResetMetadataInterface::CUSTOM_TABLE_LIKE);

        return $connection->fetchAll($select);
    }
}
