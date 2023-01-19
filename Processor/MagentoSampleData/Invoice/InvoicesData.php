<?php

namespace Magedia\Demo\Processor\MagentoSampleData\Invoice;

use Magento\Framework\DB\Transaction;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class InvoicesData
{
    private OrderRepositoryInterface $orderRepository;
    private InvoiceService $invoiceService;
    private Transaction $transaction;
    private OrderCollectionFactory $orderCollectionFactory;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InvoiceService $invoiceService,
        Transaction $transaction,
        OrderCollectionFactory $orderCollectionFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    public function createInvoices(): void
    {
        $orderCollection = $this->orderCollectionFactory->create()->addAttributeToSelect('*')->load();
        foreach ($orderCollection->getItems() as $orderItem) {
            $order = $this->orderRepository->get($orderItem->getId());
            if ($order->canInvoice()) {
                $invoice = $this->invoiceService->prepareInvoice($order);
                $invoice->register();
                $invoice->save();
                $transactionSave = $this->transaction->addObject(
                    $invoice
                )->addObject(
                    $invoice->getOrder()
                );
                $transactionSave->save();
            }
        }
    }
}
