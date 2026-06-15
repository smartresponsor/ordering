<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Order\OrderEntity;

final readonly class OrderRefundLedger
{
    public function __construct(
        private OrderEntity $order,
        private string $idempotencyKey,
        private string $amount,
        private string $currency,
    ) {
    }

    public function order(): OrderEntity
    {
        return $this->order;
    }

    public function idempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}
