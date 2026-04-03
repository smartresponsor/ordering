<?php

declare(strict_types=1);

namespace App\ApiResource\View\Order;

final class ReturnRequestInput
{
    public ?string $reason = null;
    /** @var array<int, mixed> */
    public array $items = [];
}
