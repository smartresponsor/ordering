<?php

declare(strict_types=1);

namespace App\Ordering\ApiResource\View\Order;

final class PricingConvertInput
{
    /** @var array<int, array<string, mixed>> */
    public array $items = [];
    public string $displayCurrency = 'USD';
    public ?int $discountAmountMinor = null;
    public ?float $discountPercent = null;
    public string $taxMode = 'flat';
    public float $taxRate = 0.0;
    /** @var array<int, array<string, mixed>> */
    public array $brackets = [];
    public bool $taxAfterDiscount = true;
}
