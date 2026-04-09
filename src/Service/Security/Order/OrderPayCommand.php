<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final readonly class OrderPayCommand
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $amount,
        public readonly string $externalRef,
    ) {
    }
}
