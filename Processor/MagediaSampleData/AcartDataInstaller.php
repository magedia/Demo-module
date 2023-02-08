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
        CustomerRepositoryInterface $customerRepository
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
    }

    public function install()
    {
        //Create sales rule
        $salesRuleModel = $this->salesRuleFactory->create();
        $salesRuleModel->loadPost(AcartDataInterface::SALESRULE_DATA);
        $salesRuleModel->setUseAutoGeneration(0);
        $this->salesRuleResource->save($salesRuleModel);

        //Create abandoned cart rule
        $rule = $this->ruleFactory->create();
        $ruleData = array_merge(AcartDataInterface::ACART_RULE_DATA, ['sales_rule_id' => $salesRuleModel->getRuleId()]);
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
