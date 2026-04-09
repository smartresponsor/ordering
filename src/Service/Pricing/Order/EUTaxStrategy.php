<?php

declare(strict_types=1);

namespace App\Service\Pricing\Order;

use App\Contract\Gateway\Order\OrderTaxationGatewayInterface;
use App\ServiceInterface\Pricing\Order\EUTaxStrategyInterface;

final class EUTaxStrategy implements EUTaxStrategyInterface, OrderTaxationGatewayInterface
{
    /** @var array<string,float> */
    private array $vat = [
        'DE' => 0.19, 'FR' => 0.20, 'ES' => 0.21, 'IT' => 0.22,
        'PL' => 0.23, 'NL' => 0.21, 'SE' => 0.25, 'HU' => 0.27, 'RO' => 0.19, 'BE' => 0.21,
    ];

    /**
     * @param array<int, array<string, mixed>> $lines
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    public function calculate(string $orderId, array $lines, array $context = []): array
    {
        $countryCode = strtoupper((string) ($context['country'] ?? 'DE'));
        $rate = $this->vat[$countryCode] ?? 0.20;
        $subtotal = $this->extractSubtotal($lines);
        $tax = round($subtotal * $rate, 2);
        $total = round($subtotal + $tax, 2);

        return [
            'orderId' => $orderId,
            'country' => $countryCode,
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'taxAmount' => number_format($tax, 2, '.', ''),
            'total' => number_format($total, 2, '.', ''),
            'currency' => (string) ($context['currency'] ?? 'USD'),
        ];
    }

    /** @param array<int, array<string, mixed>> $lines */
    private function extractSubtotal(array $lines): float
    {
        $subtotal = 0.0;

        foreach ($lines as $line) {
            $price = (float) ($line['price'] ?? 0.0);
            $quantity = max(1, (int) ($line['quantity'] ?? 1));
            $subtotal += $price * $quantity;
        }

        return $subtotal;
    }
}
