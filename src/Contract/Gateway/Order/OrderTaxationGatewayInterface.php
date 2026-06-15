<?php

declare(strict_types=1);

namespace App\Contract\Gateway\Order;

interface OrderTaxationGatewayInterface
{
    /**
     * @param array<int, array<string, mixed>> $lines
     * @param array<string, mixed>             $context
     *
     * @return array<string, mixed>
     */
    public function calculate(string $orderId, array $lines, array $context = []): array;
}
