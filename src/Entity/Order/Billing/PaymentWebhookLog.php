<?php

declare(strict_types=1);

namespace App\Entity\Order\Billing;

final class PaymentWebhookLog
{
    public function __construct(private string $key) {}
    public function key(): string { return $this->key; }
}
