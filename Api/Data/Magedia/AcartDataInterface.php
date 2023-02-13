<?php

declare(strict_types=1);

namespace Magedia\Demo\Api\Data\Magedia;

interface AcartDataInterface
{
    public const ACART_RULE_DATA = [
        'send_after' => '{"qty":"5","duration":"minute"}',
        'is_low_stock_notification' => '1',
        'low_stock_qty' => '3',
        'is_out_of_stock_notification' => '0',
        'out_of_stock_template_id' => null,
        'low_stock_template_id' => null,
        'in_stock_template_id' => null,
        'expire_in' => null,
    ];

    public const ACART_MAIL_DATA = [
        'rule_name' => 'Buy 3 bags and get 1 fo free',
        'customer_email' => 'test@example.com',
        'first_name' => 'Veronica',
        'last_name' => 'Costello',
        'email_status' => 1
    ];

    public const ACART_STATISTIC_DATA = [
        'full_name' => 'Veronica Costello',
        'thrown_cart_stage' => 'Bag',
        'customer_email' => 'test@example.com',
        'store_id' => 1,
        'email_quantity' => 3,
        'coupon_used' => 2
    ];

    public const ACART_STOCK_ALERT_DATA = [
        'product_id' => 7,
        'customer_email' => 'test@example.com',
        'website_id' => 1,
        'store_id' => 1,
        'coupon_used' => 2,
        'status' => 1,
    ];

    public const SALESRULE_DATA = [
        'coupon_code' => '5GDPE32',
        'uses_per_coupon' => '',
        'uses_per_customer' => '',
        'discount_amount' => '12',
        'discount_qty' => '',
        'discount_step' => '',
        'apply_to_shipping' => '0',
        'simple_action' => 'by_percent',
        'coupon_type' => 2,
        'name' => 'Buy 3 bags and get 1 fo free',
        'sort_order' => '0',
        'is_active' => '1',
        'website_ids' => '1',
        'customer_group_ids' => '1',
        'conditions' => '',
        'from_date' => '2/8/23',
    ];
}
