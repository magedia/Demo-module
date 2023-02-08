<?php

declare(strict_types=1);

namespace Magedia\Demo\Api\Data\Magedia;

interface AcartConfigInterface
{
    public const ACART_CONFIG = [
        "magedia_acart/general/cron_frequency" => '0 * * * *',
        "magedia_acart/general/quote_lifetime" => 25,
        "magedia_acart/general/sender_name" => '',
        "magedia_acart/general/sender_email" => 'test@example.com',
        "magedia_acart/cleaning/apply_mail_cleaning" => 0,
        "magedia_acart/stock/send_alert_stock_cron_frequency" => '0 * * * *',
        "magedia_acart/stock/remove_alert_stock_cron_frequency" => '0 * * * *'
    ];
}
