<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Pricing\Order;

use App\Ordering\ValueObject\Pricing\Order\TaxRate;

interface TaxationConfigLoaderInterface
{
    public function __construct(string $path);

    public function rounding(): int;

    public function defaultCurrency(): string;

    public function rateFor(string $region, ?string $subregion = null): TaxRate;
}
