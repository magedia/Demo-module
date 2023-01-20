<?php

namespace Magedia\Demo\Processor\MagentoSampleData\CreditMemo;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Model\Service\CreditmemoService;

class CreditMemosData
{
    private OrderRepositoryInterface $orderRepository;
    private OrderCollectionFactory $orderCollectionFactory;
    private CreditmemoService $creditMemoService;
    private CreditmemoFactory $creditMemoFactory;

    public function __construct(
        CreditmemoFactory $creditMemoFactory,
        OrderRepositoryInterface $orderRepository,
        CreditmemoService $creditMemoService,
        OrderCollectionFactory $orderCollectionFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->creditMemoService = $creditMemoService;
        $this->creditMemoFactory = $creditMemoFactory;
    }

    public function createCreditMemos(): void
    {
        $orderCollection = $this->orderCollectionFactory->create()->addAttributeToSelect('*')->load();
        foreach ($orderCollection->getItems() as $orderItem) {
            $order = $this->orderRepository->get($orderItem->getId());
            $invoices = $order->getInvoiceCollection();
            foreach ($invoices as $invoice) {
                $invoiceIncrementId = $invoice->getIncrementId();
                $invoiceData = $invoice->loadByIncrementId($invoiceIncrementId);
                $creditMemo = $this->creditMemoFactory->createByOrder($order);
                $creditMemo->setInvoice($invoiceData);
                $creditMemo->save();
            }
        }
    }
}
