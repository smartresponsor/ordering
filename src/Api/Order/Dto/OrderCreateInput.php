<?php

declare(strict_types=1);

namespace App\Api\Order\Dto;

final class OrderCreateInput
{
    public string $vendorId = '';
    public string $currency = 'USD';

    /** @var array<int, array<string, mixed>> */
    public array $items = [];
}
