<?php

declare(strict_types=1);

namespace Magedia\Demo\Api\Data\Magedia;

interface ConfigInterface
{
    public const PDF_INVOICE_CONFIG = [
        "magedia_pdfinvoice/credit_memo/admin_bulk_print_button_label" => "PDF Invoice: Print Credit Memos",
        "magedia_pdfinvoice/credit_memo/admin_print_button_label" => "PDF Invoice: Print Credit Memo",
        "magedia_pdfinvoice/credit_memo/attachment" => 0,
        "magedia_pdfinvoice/credit_memo/enable" => 1,
        "magedia_pdfinvoice/credit_memo/show_in_customer_account" => "No",
        "magedia_pdfinvoice/general/core_newkey" => null,
        "magedia_pdfinvoice/general/enable" => 1,
        "magedia_pdfinvoice/general/is_debug" => 0,
        "magedia_pdfinvoice/invoice/admin_bulk_print_button_label" => "PDF Invoice: Print Invoices",
        "magedia_pdfinvoice/invoice/admin_print_button_label" => "PDF Invoice: Print Invoice",
        "magedia_pdfinvoice/invoice/attachment" => 0,
        "magedia_pdfinvoice/invoice/enable" => 1,
        "magedia_pdfinvoice/invoice/show_in_customer_account" => "No",
        "magedia_pdfinvoice/order/admin_bulk_print_button_label" => "PDF Invoice: Print Orders",
        "magedia_pdfinvoice/order/admin_print_all_button_label" => "PDF Invoice: Print All",
        "magedia_pdfinvoice/order/admin_print_button_label" => "PDF Invoice: Print Order",
        "magedia_pdfinvoice/order/attachment" => 0,
        "magedia_pdfinvoice/order/enable" => 1,
        "magedia_pdfinvoice/order/show_in_customer_account" => "No",
        "magedia_pdfinvoice/shipment/admin_bulk_print_button_label" => "PDF Invoice: Print Shipments",
        "magedia_pdfinvoice/shipment/admin_print_button_label" => "PDF Invoice: Print Shipment",
        "magedia_pdfinvoice/shipment/attachment" => 0,
        "magedia_pdfinvoice/shipment/enable" => 1,
        "magedia_pdfinvoice/shipment/show_in_customer_account" => "No",
    ];
}
