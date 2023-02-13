<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\MagediaSampleData;

use Magedia\AbandonedCart\Api\MailJournalRepositoryInterface;
use Magedia\AbandonedCart\Api\RuleRepositoryInterface;
use Magedia\AbandonedCart\Api\StatisticRepositoryInterface;
use Magedia\AbandonedCart\Api\StockRepositoryInterface;
use Magedia\AbandonedCart\Model\MailJournalFactory;
use Magedia\AbandonedCart\Model\RuleFactory;
use Magedia\AbandonedCart\Model\StatisticFactory;
use Magedia\AbandonedCart\Model\StockFactory;
use Magedia\Demo\Api\Data\Magedia\AcartDataInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Email\Model\ResourceModel\Template as TemplateResource;
use Magento\Framework\Mail\TemplateInterface;
use Magento\Framework\Mail\TemplateInterfaceFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\SalesRule\Model\ResourceModel\Rule as SalesRuleResource;
use Magento\SalesRule\Model\RuleFactory as SalesRuleFactory;

class AcartDataInstaller
{
    private RuleRepositoryInterface $ruleRepository;
    private MailJournalRepositoryInterface $journalRepository;
    private StatisticRepositoryInterface $statisticRepository;
    private StockRepositoryInterface $stockRepository;
    private MailJournalFactory $journalFactory;
    private RuleFactory $ruleFactory;
    private StatisticFactory $statisticFactory;
    private StockFactory $stockFactory;
    private SalesRuleFactory $salesRuleFactory;
    private SalesRuleResource $salesRuleResource;
    private QuoteFactory $quoteFactory;
    private Product $product;
    private CustomerRepositoryInterface $customerRepository;
    private TemplateResource $templateResource;
    private TemplateInterfaceFactory $templateInterfaceFactory;

    private const EMAIL_TEMPLATE = <<<HTML
<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">

    <style type="text/css">
        table{
            font-family: "Arial", sans-serif !important;
        }
    </style>
</head>

<body>
<table width="600" style="padding: 0 20px;margin: 0 auto;">
    <tr style="text-align: center;">
        <td>
            <img src="{{view url='Magedia_AbandonedCart/images/logo.png'}}" alt="">
        </td>
    </tr>

    <tr style="text-align: center;font-size: 16px;font-weight: bold;">
        <td style="display: block;margin-top: 20px;">
            {{trans "Have a nice day"}} {{var customer_name}}!
        </td>
    </tr>

    <tr style="font-size: 16px;margin-bottom: 20px; display: block;">
        <td style="display: block;margin-top: 20px;">
            {{trans "I noticed that you have added several of great items but haven’t complete the checkout yet? So I want to make sure if below is your forgotten wishlist"}}
        </td>
    </tr>
</table>
<table width="560" style="background-color: #fff5e9; padding: 10px 20px;margin: 0 auto;">
    <tr style="font-size: 16px;text-transform: uppercase;color: #484850;font-weight: bold;">
        <td style="width: 75px;text-align: center;padding-right: 55px;">
            {{trans "IMG."}}
        </td>
        <td style="width: 220px;">
            {{trans "Items"}}
        </td>
        <td style="width: 50px; text-align: center;">
            {{trans "Qty"}}
        </td>
        <td style="width: 70px; text-align: center;">
            {{trans "Price"}}
        </td>
    </tr>
</table>
{{for item in products}}
<table width="560" style="margin: 0 auto; padding: 10px 20px; border-bottom: 1px solid #dedee0;">
    <tr style="font-size: 16px;">
        <td style="width: 75px; text-align: center;padding-right: 55px;">
            <img style="width: 75px" src="{{var item.img}}" alt="">
        </td>
        <td style="width: 220px;">
            <span style="display: block;font-size: 14px;color: #484850;padding-bottom: 5px;">{{var item.product_name}}</span>
            <span style="font-size: 14px; color: #A4A4A8;">SKU: {{var item.sku}}</span>
        </td>
        <td style="font-size: 14px;color: #484850;width: 50px; text-align: center;vertical-align: top; padding-top: 18px;">
            {{var item.qty}}
        </td>
        <td style="font-size: 14px;color: #484850;width: 70px; text-align: center;vertical-align: top; padding-top: 18px;">
            {{var item.price}}
        </td>
    </tr>
</table>
{{/for}}
<table width="560" style="margin: 0 auto;padding-top: 20px;padding-bottom: 40px;">
    <tbody style="float: right;">
    <tr>
        <td style="width: 160px;color: #484850;font-size: 14px;font-weight: bold">{{trans "Subtotal"}}</td>
        <td style="color: #484850;font-size: 14px;">{{var subtotal}}</td>
    </tr>
    <tr>
        <td style="color: #484850;font-size: 14px;font-weight: bold">{{trans "Shipping & Handling"}}</td>
        <td style="color: #484850;font-size: 14px;padding-left: 10px;">{{var shipping_handling}}</td>
    </tr>
    <tr>
        <td style="color: #484850;font-size: 14px;font-weight: bold">{{trans "Tax"}}</td>
        <td style="color: #484850;font-size: 14px;padding-left: 10px;">{{var tax}}</td>
    </tr>
    <tr>
        <td style="color: #484850;font-size: 14px;font-weight: bold">{{trans "Grand Total"}}</td>
        <td style="color: #484850;font-size: 14px;padding-left: 1px;">
            <b>{{var grand_Total}}</b>
        </td>
    </tr>
    </tbody>
</table>
<table width="600" style="padding: 0 20px;margin: 0 auto;">
    <tr style="text-align: center;">
        <td>
            <a style="background: linear-gradient(180deg, #FFAA3C 0%, #FD8911 100%); border-radius: 5px;color: #FFFFFF;text-decoration: none;padding: 13px 33px;font-weight: bold"
               href="{{var button_url}}" target="_blank">
                {{trans "Your cart here"}}
            </a>
        </td>
    </tr>

    <tr style="font-size: 16px;display: block; color: #484850;">
        <td style="display: block;margin-top: 30px;margin-bottom: 10px;">
            {{trans "How about receiving the"}} <b>{{trans "coupon"}} <span style="color: #FFAA3C;">{{var coupon_code}}</span></b> {{trans "discount to enlighten your day with us"}} ?
        </td>
        <td style="display: block;margin-bottom: 10px;">
            {{trans "It’ll be available to"}} {{var expire_time}}
        </td>
        <td style="display: block;">
            {{trans "Thanks for thinking about Main Website Store‘s offers again. We hope to see your response soon."}}
        </td>
    </tr>
</table>
</body>
</html>
HTML;

    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        MailJournalRepositoryInterface $journalRepository,
        StatisticRepositoryInterface $statisticRepository,
        StockRepositoryInterface $stockRepository,
        MailJournalFactory $journalFactory,
        RuleFactory $ruleFactory,
        StatisticFactory $statisticFactory,
        StockFactory $stockFactory,
        SalesRuleFactory $salesRuleFactory,
        SalesRuleResource $salesRuleResource,
        QuoteFactory $quoteFactory,
        Product $productModel,
        CustomerRepositoryInterface $customerRepository,
        TemplateResource $templateResource,
        TemplateInterfaceFactory $templateInterfaceFactory
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->journalRepository = $journalRepository;
        $this->statisticRepository = $statisticRepository;
        $this->stockRepository = $stockRepository;
        $this->journalFactory = $journalFactory;
        $this->ruleFactory = $ruleFactory;
        $this->statisticFactory = $statisticFactory;
        $this->stockFactory = $stockFactory;
        $this->salesRuleFactory = $salesRuleFactory;
        $this->salesRuleResource = $salesRuleResource;
        $this->product = $productModel;
        $this->quoteFactory = $quoteFactory;
        $this->customerRepository = $customerRepository;
        $this->templateResource = $templateResource;
        $this->templateInterfaceFactory = $templateInterfaceFactory;
    }

    public function install()
    {
        $template = $this->templateInterfaceFactory->create();
        $template->setTemplateCode('Magedia Abandoned Cart')
            ->setTemplateSubject('Abandoned Cart')
            ->setTemplateText(self::EMAIL_TEMPLATE)
            ->setTemplateType(TemplateInterface::TYPE_HTML);
        $this->templateResource->save($template);

        //Create sales rule
        $salesRuleModel = $this->salesRuleFactory->create();
        $salesRuleModel->loadPost(AcartDataInterface::SALESRULE_DATA);
        $salesRuleModel->setUseAutoGeneration(0);
        $this->salesRuleResource->save($salesRuleModel);

        //Create abandoned cart rule
        $rule = $this->ruleFactory->create();
        $ruleData = array_merge(
            AcartDataInterface::ACART_RULE_DATA,
            ['sales_rule_id' => $salesRuleModel->getRuleId()],
            ['email_template_id' => $template->getId()],
        );
        $rule->setData($ruleData);
        $rule->loadPost($ruleData);
        $this->ruleRepository->save($rule);

        //Create quote
        $customer = $this->customerRepository->getById(1);
        $quote = $this->quoteFactory->create();
        $product = $this->product->load(1);
        $quote->setStoreId(1);
        $quote->assignCustomer($customer);
        $quote->addProduct($product);
        $quote->setCustomerEmail('roni_cost@example.com');
        $quote->setPaymentMethod('checkmo');
        $quote->getPayment()->setQuote($quote);
        $quote->getPayment()->importData(['method' => 'checkmo']);
        $quote->setInventoryProcessed(false);
        $quote->save();

        //Create mailjournal record
        $mailjournal = $this->journalFactory->create();
        $mailData = array_merge(
            AcartDataInterface::ACART_MAIL_DATA,
            ['rule_id' => $rule->getId()],
            ['quote_id' => $quote->getId()]
        );
        $this->journalRepository->save($mailjournal->setData($mailData));

        //Create statistic record
        $statistic = $this->statisticFactory->create();
        $statisticData = array_merge(
            AcartDataInterface::ACART_STATISTIC_DATA,
            ['quote_id' => $quote->getId()]
        );
        $this->statisticRepository->save($statistic->setData($statisticData));

        //Create stock record
        $stock = $this->stockFactory->create();
        $stockData = array_merge(
            AcartDataInterface::ACART_STOCK_ALERT_DATA,
            ['quote_id' => $quote->getId()],
            ['rule_id' => $rule->getId()],
        );
        $this->stockRepository->save($stock->setData($stockData));
    }
}
