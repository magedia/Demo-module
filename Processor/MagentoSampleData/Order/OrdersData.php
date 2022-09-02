<?php

namespace Magedia\Demo\Processor\MagentoSampleData\Order;

use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;

class OrdersData
{
    const ORDERS_SAMPLE_DATA = [
        [
            'currency_id' => 'USD',
            'email' => 'john_smith@order.com',
            'shipping_address' => [
                'firstname' => 'John',
                'lastname' => 'Smith',
                'street' => 'Green str, 67',
                'city' => 'CityM',
                'country_id' => 'US',
                'region_id' => '1',
                'region' => 'Alabama',
                'postcode' => '46373',
                'telephone' => '3468676',
                'fax' => '32423',
                'save_in_address_book' => 1
            ],
            'items' => [
                ['product_id' => '7', 'qty' => 1],
                ['product_id' => '4', 'qty' => 1]
            ]
        ],
        [
            'currency_id' => 'USD',
            'email' => 'veronica_costello@order.com',
            'shipping_address' => [
                'firstname' => 'Veronica',
                'lastname' => 'Costello',
                'street' => '6146 Honey Bluff Parkway',
                'city' => 'Calder',
                'country_id' => 'US',
                'region_id' => '5',
                'region' => 'Michigan',
                'postcode' => '49628-7978',
                'telephone' => '77789',
                'fax' => '32423',
                'save_in_address_book' => 1
            ],
            'items' => [
                ['product_id' => '6', 'qty' => 1],
                ['product_id' => '8', 'qty' => 1],
                ['product_id' => '3', 'qty' => 1],
                ['product_id' => '9', 'qty' => 1]
            ]
        ],
        [
            'currency_id' => 'USD',
            'email' => 'roni_cost@order.com',
            'shipping_address' => [
                'firstname' => 'Roni',
                'lastname' => 'Cost',
                'street' => 'Order street, 12',
                'city' => 'Ordercity',
                'country_id' => 'US',
                'region_id' => '2',
                'region' => 'Alaska',
                'postcode' => '43244',
                'telephone' => '52332',
                'fax' => '32423',
                'save_in_address_book' => 1
            ],
            'items' => [
                ['product_id' => '14', 'qty' => 1],
                ['product_id' => '20', 'qty' => 1]
            ]
        ],
    ];

    private StoreManagerInterface $_storeManager;
    private ProductFactory $_productFactory;
    private CustomerFactory $customerFactory;
    private CartRepositoryInterface $cartRepositoryInterface;
    private CustomerRepositoryInterface $customerRepository;
    private CartManagementInterface $cartManagementInterface;
    private Rate $shippingRate;
    private QuoteFactory $quote;
    private QuoteManagement $quoteManagment;

    public function __construct(
        StoreManagerInterface       $storeManager,
        ProductFactory              $productFactory,
        CustomerFactory             $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        CartRepositoryInterface     $cartRepositoryInterface,
        CartManagementInterface     $cartManagementInterface,
        Rate                        $shippingRate,
        QuoteFactory $quote,
        QuoteManagement $quoteManagment
    ) {
        $this->_storeManager = $storeManager;
        $this->_productFactory = $productFactory;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
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
    public function createOrders()
    {
        $store = $this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();

        foreach (self::ORDERS_SAMPLE_DATA as $order) {
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
