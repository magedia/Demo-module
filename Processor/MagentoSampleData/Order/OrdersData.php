<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\MagentoSampleData\Order;

use Magedia\Demo\Api\Data\Magento\OrderInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Store\Model\StoreManagerInterface;

class OrdersData
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $_storeManager;

    /**
     * @var ProductFactory
     */
    private ProductFactory $_productFactory;

    /**
     * @var CustomerFactory
     */
    private CustomerFactory $customerFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var Rate
     */
    private Rate $shippingRate;

    /**
     * @var QuoteFactory
     */
    private QuoteFactory $quote;

    /**
     * @var QuoteManagement
     */
    private QuoteManagement $quoteManagment;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ProductFactory $productFactory
     * @param CustomerFactory $customerFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param Rate $shippingRate
     * @param QuoteFactory $quote
     * @param QuoteManagement $quoteManagment
     */
    public function __construct(
        StoreManagerInterface       $storeManager,
        ProductFactory              $productFactory,
        CustomerFactory             $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        Rate                        $shippingRate,
        QuoteFactory $quote,
        QuoteManagement $quoteManagment
    ) {
        $this->_storeManager = $storeManager;
        $this->_productFactory = $productFactory;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->shippingRate = $shippingRate;
        $this->quote = $quote;
        $this->quoteManagment = $quoteManagment;
    }

    /**
     * Create Orders
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws \Exception
     */
    public function createOrders(): void
    {
        $store = $this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();

        foreach (OrderInterface::ORDERS_SAMPLE_DATA as $order) {
            $customer = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->loadByEmail($order['email']);
            if (!$customer->getEntityId()) {
                $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($order['shipping_address']['firstname'])
                    ->setLastname($order['shipping_address']['lastname'])
                    ->setEmail($order['email'])
                    ->setPassword($order['email'])
                    ->setCity($order['shipping_address']['city'])
                    ->setPostcode($order['shipping_address']['postcode'])
                    ->setTelephone($order['shipping_address']['telephone']);
                $customer->save();
            }

            $quote = $this->quote->create();
            $quote->setStore($store);
            $quote->setStoreId(1);
            $customer = $this->customerRepository->getById($customer->getEntityId());
            $quote->setCurrency();
            $quote->assignCustomer($customer);

            foreach ($order['items'] as $item) {
                $product = $this->_productFactory->create()->load($item['product_id']);
                $quote->addProduct(
                    $product,
                    intval($item['qty'])
                );
            }

            $quote->getBillingAddress()->addData($order['shipping_address']);
            $quote->getShippingAddress()->addData($order['shipping_address']);

            $this->shippingRate
                ->setCode('freeshipping_freeshipping')
                ->getPrice(1);
            $shippingAddress = $quote->getShippingAddress();

            $shippingAddress->setCollectShippingRates(true)
                ->collectShippingRates()
                ->setShippingMethod('flatrate_flatrate');

            $quote->getShippingAddress()->addShippingRate($this->shippingRate);
            $quote->setInventoryProcessed(false);
            $quote->save();
            $quote->getPayment()->importData(['method' => 'checkmo']);

            $quote->collectTotals()->save();
            $this->quoteManagment->submit($quote);
        }
    }
}
