<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Order as RootOrder;

final class OrderRefundLedger
{
    private string $orderId;
    private string $idempotencyKey;
    private string $amount;
    private string $currency;

    public function __construct(RootOrder|string $order, string $idempotencyKey, string|int|float $amount, string $currency)
    {
        $this->orderId = $order instanceof RootOrder ? $order->getId() : $order;
        $this->idempotencyKey = $idempotencyKey;
        $this->amount = number_format((float) $amount, 2, '.', '');
        $this->currency = strtoupper($currency);
    }

    public function orderId(): string { return $this->orderId; }
    public function amount(): string { return $this->amount; }
    public function currency(): string { return $this->currency; }
    public function idempotencyKey(): string { return $this->idempotencyKey; }
}
