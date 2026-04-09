<?php

declare(strict_types=1);

namespace App\Entity\Order\Billing;

final readonly class OrderInvoice
{
    public function __construct(
        private readonly string $orderId,
        private readonly string $amount,
        private readonly string $currency,
    ) {
    }
}
