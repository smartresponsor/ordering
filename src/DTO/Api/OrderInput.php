<?php

declare(strict_types=1);

namespace App\Ordering\DTO\Api;

final class OrderInput
{
    /**
     * @var list<array<string, mixed>>
     */
    public array $items = [];

    public string $currency = 'USD';
}
