<?php

namespace Magedia\Demo\Model\Reset\MagentoTables\Order;

use Magedia\Demo\Model\Reset\AbstractTableGetter;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;

class SalesTables extends AbstractTableGetter
{
    const SALES_ORDER_TABLES = [
        'quote',
        'quote_address',
        'quote_address_item',
        'quote_id_mask',
        'quote_item',
        'quote_item_option',
        'quote_payment',
        'quote_shipping_rate',
        'reporting_orders',
        'sales_bestsellers_aggregated_daily',
        'sales_bestsellers_aggregated_monthly',
        'sales_bestsellers_aggregated_yearly',
        'sales_creditmemo',
        'sales_creditmemo_comment',
        'sales_creditmemo_grid',
        'sales_creditmemo_item',
        'sales_invoice',
        'sales_invoiced_aggregated',
        'sales_invoiced_aggregated_order',
        'sales_invoice_comment',
        'sales_invoice_grid',
        'sales_invoice_item',
        'sales_order',
        'sales_order_address',
        'sales_order_aggregated_created',
        'sales_order_aggregated_updated',
        'sales_order_grid',
        'sales_order_item',
        'sales_order_payment',
        'sales_order_status_history',
        'sales_order_tax',
        'sales_order_tax_item',
        'sales_payment_transaction',
        'sales_refunded_aggregated',
        'sales_refunded_aggregated_order',
        'sales_shipment',
        'sales_shipment_comment',
        'sales_shipment_grid',
        'sales_shipment_item',
        'sales_shipment_track',
        'sales_shipping_aggregated',
        'sales_shipping_aggregated_order',
        'tax_order_aggregated_created',
        'tax_order_aggregated_updated',
    ];

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
            ->where('table_name in( ? )', self::SALES_ORDER_TABLES);

        return $connection->fetchAll($select);
    }
}
