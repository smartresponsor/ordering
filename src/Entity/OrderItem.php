<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Pricing\Order\Sku;
use App\ValueObject\Pricing\Order\Quantity;

final class OrderItem
{
    private ?Order $order = null;
    private string $sku;
    private int $quantity;
    private string $basePrice;
    private string $currency;

    public function __construct(mixed $arg1 = 'SKU-1', mixed $arg2 = 1, mixed $arg3 = '0.00', mixed $arg4 = 'USD')
    {
        if ($arg1 instanceof Order) {
            $this->order = $arg1;
            $sku = $arg2;
            $quantity = $arg3;
            $basePrice = $arg4;
            $currency = 'USD';
        } else {
            $sku = $arg1;
            $quantity = $arg2;
            $basePrice = $arg3;
            $currency = $arg4;
        }

        $this->sku = $sku instanceof Sku ? (string) $sku : (string) $sku;

        if ($quantity instanceof Quantity) {
            $this->quantity = $quantity->toInt();
        } elseif (is_numeric($quantity)) {
            $this->quantity = max(1, (int) $quantity);
        } else {
            $this->quantity = 1;
        }

        if (is_object($basePrice) && method_exists($basePrice, 'getAmount') && method_exists($basePrice, 'getCurrency')) {
            $this->basePrice = number_format((float) $basePrice->getAmount(), 2, '.', '');
            $this->currency = strtoupper((string) $basePrice->getCurrency());
            return;
        }

        if (is_int($basePrice) && $basePrice > 1000) {
            $this->basePrice = number_format($basePrice / 100, 2, '.', '');
        } else {
            $this->basePrice = number_format((float) $basePrice, 2, '.', '');
        }
        $this->currency = strtoupper((string) $currency);
    }

    public function getSku(): string { return $this->sku; }
    public function getQuantity(): int { return $this->quantity; }
    public function getBasePrice(): string { return $this->basePrice; }
    public function getCurrency(): string { return $this->currency; }
    public function getUnitPrice(): string { return $this->basePrice; }
}
