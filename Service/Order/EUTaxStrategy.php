<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\Contract\Gateway\Order\OrderTaxationGatewayInterface;
use App\Contract\Gateway\Order\TaxBreakdown;
use App\Entity\Order;
use App\ServiceInterface\Order\EUTaxStrategyInterface;

final class EUTaxStrategy implements EUTaxStrategyInterface, OrderTaxationGatewayInterface
{
    /** @var array<string,float> */
    private array $vat = [
        'DE' => 0.19, 'FR' => 0.20, 'ES' => 0.21, 'IT' => 0.22,
        'PL' => 0.23, 'NL' => 0.21, 'SE' => 0.25, 'HU' => 0.27, 'RO' => 0.19, 'BE' => 0.21,
    ];

    public function calculate(Order $order, ?string $countryCode = null): TaxBreakdown
    {
        $rate = $this->vat[$countryCode ?? 'DE'] ?? 0.20;
        $subtotal = (float) $order->getTotalAmount();
        $tax = round($subtotal * $rate, 2);
        $total = round($subtotal + $tax, 2);

        return new TaxBreakdown(
            number_format($subtotal, 2, '.', ''),
            number_format($tax, 2, '.', ''),
            number_format($total, 2, '.', ''),
            $order->getCurrency()
        );
    }
}
