<?php

declare(strict_types=1);

namespace App\ApiResource\Order;

final class ReturnRequestInput
{
    public ?string $reason = null;
    /** @var array<int, mixed> */
    public array $items = [];
}
