<?php

declare(strict_types=1);

namespace App\Contract\Order;

interface OrderTaxationGatewayInterface
{
    public function calculate(string $orderId, array $lines, array $context = []): array;
}
