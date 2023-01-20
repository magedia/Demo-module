<?php

namespace Magedia\Demo\Processor\MagentoSampleData\Shipment;

use Exception;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Convert\Order as OrderConverter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Psr\Log\LoggerInterface;

class ShipmentsData
{
    private OrderRepositoryInterface $orderRepository;
    private OrderCollectionFactory $orderCollectionFactory;
    private OrderConverter $orderConverter;
    private LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderCollectionFactory $orderCollectionFactory,
        OrderConverter $orderConverter,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderConverter = $orderConverter;
        $this->logger = $logger;
    }

    public function createShipments(): void
    {
        $orderCollection = $this->orderCollectionFactory->create()->addAttributeToSelect('*')->load();
        foreach ($orderCollection->getItems() as $orderCollectionItem) {
            $order = $this->orderRepository->get($orderCollectionItem->getId());
            if (!$order->canShip()) {
                continue;
            }
            $shipment = $this->orderConverter->toShipment($order);
            foreach ($order->getAllItems() as $orderItem) {
                if (! $orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                    continue;
                }
                $qtyShipped = $orderItem->getQtyToShip();
                $shipmentItem = $this->orderConverter->itemToShipmentItem($orderItem)->setQty($qtyShipped);
                $shipment->addItem($shipmentItem);
            }
            $shipment->register();
            $shipment->getOrder()->setIsInProcess(true);
            try {
                $shipment->save();
                $shipment->getOrder()->save();
            } catch (Exception $e) {
                $this->logger->info($e->getMessage());
            }
        }
    }
}
