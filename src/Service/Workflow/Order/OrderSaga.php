<?php

declare(strict_types=1);

namespace App\Ordering\Service\Workflow\Order;

use App\Ordering\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\Ordering\Contract\Gateway\Order\OrderShipmentGatewayInterface;
use App\Ordering\Contract\Gateway\Order\OrderTaxationGatewayInterface;
use App\Ordering\Entity\Order\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final readonly class OrderSaga
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderPaymentGatewayInterface $payment,
        private OrderShipmentGatewayInterface $shipment,
        private OrderTaxationGatewayInterface $taxation,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function execute(OrderEntity $order): void
    {
        try {
            $amount = $order->getTotalAmount();
            $currency = $order->getCurrency();
            $orderId = $order->getNumber();

            $this->payment->charge($orderId, $amount, ['currency' => $currency]);
            $order->markAsPaid();

            $tracking = $this->shipment->ship($orderId, 'DHL', ['currency' => $currency]);
            $order->assignTracking($tracking);
            $order->markAsShipped();

            $taxBreakdown = $this->taxation->calculate($orderId, $this->buildLines($order), ['country' => 'DE', 'currency' => $currency]);
            $taxAmount = $this->extractDecimal($taxBreakdown['taxAmount'] ?? $taxBreakdown['tax'] ?? null);
            $totalAmount = $this->extractDecimal($taxBreakdown['total'] ?? $amount);
            $order->setTaxAmount($taxAmount);
            $order->setTotalAmount($totalAmount);

            $order->markAsCompleted();
            $this->em->flush();
        } catch (\Throwable $e) {
            $this->logger?->error('OrderSaga failed', ['order' => $order->getNumber(), 'e' => $e->getMessage()]);
            throw $e;
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function buildLines(OrderEntity $order): array
    {
        $lines = [];

        foreach ($order->getItems() as $item) {
            $lines[] = [
                'sku' => method_exists($item, 'getSku') ? $item->getSku() : null,
                'quantity' => method_exists($item, 'getQuantity') ? $item->getQuantity() : null,
                'price' => method_exists($item, 'getUnitPrice') ? $item->getUnitPrice() : null,
            ];
        }

        if ([] === $lines) {
            $lines[] = ['price' => $order->getSubtotal()];
        }

        return $lines;
    }

    private function extractDecimal(mixed $value): string
    {
        if (is_numeric($value)) {
            return number_format((float) $value, 2, '.', '');
        }

        return '0.00';
    }
}
