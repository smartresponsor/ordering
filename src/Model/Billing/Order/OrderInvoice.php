<?php

declare(strict_types=1);

namespace App\Model\Billing\Order;

final readonly class OrderInvoice
{
    public function __construct(
        private string $orderId,
        private string $total,
        private string $currency,
    ) {
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getTotal(): string
    {
        return $this->total;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
