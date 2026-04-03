<?php

declare(strict_types=1);

namespace App\Service\Workflow\Order;

use App\ServiceInterface\Order\OrderPaymentGatewayInterface;
use App\ServiceInterface\Order\OrderShipmentGatewayInterface;
use App\Contract\Gateway\Order\OrderTaxationGatewayInterface;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final class OrderSaga
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OrderPaymentGatewayInterface $payment,
        private readonly OrderShipmentGatewayInterface $shipment,
        private readonly OrderTaxationGatewayInterface $taxation,
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    public function execute(Order $order): void
    {
        try {
            // payment
            $this->payment->initiatePayment($order->getNumber(), (float) $order->getTotalAmount(), $order->getCurrency());
            if (method_exists($order, 'markAsPaid')) {
                $order->markAsPaid();
            }

            // shipment
            $tracking = $this->shipment->createShipment($order, 'DHL');
            if (method_exists($order, 'assignTracking')) {
                $order->assignTracking($tracking);
            }

            // taxation
            $b = $this->taxation->calculate($order, 'DE');
            if (method_exists($order, 'setTaxAmount')) {
                $order->setTaxAmount($b->taxAmount);
            }
            if (method_exists($order, 'setTotalAmount')) {
                $order->setTotalAmount($b->total);
            }

            if (method_exists($order, 'markAsCompleted')) {
                $order->markAsCompleted();
            }
            $this->em->flush();
        } catch (\Throwable $e) {
            $this->logger?->error('OrderSaga failed', ['order' => $order->getNumber(), 'e' => $e->getMessage()]);
            throw $e;
        }
    }
}
