<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

readonly class OrderPartialShipCommand
{
    public function __construct(
        public readonly string $orderId,
        public readonly int $count,
        public readonly ?string $note = null,
    ) {
    }
}
