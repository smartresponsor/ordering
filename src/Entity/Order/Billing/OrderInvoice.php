<?php

declare(strict_types=1);

namespace App\Entity\Order\Billing;

final class OrderInvoice
{
    public function __construct(private string $orderId, private string $amount, private string $currency) {}
}
