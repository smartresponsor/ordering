<?php

declare(strict_types=1);

namespace App\State\Api;

final readonly class OrderPriceProvider
{
    public function __construct(private mixed $connection)
    {
    }

    /** @return array<string, mixed> */
    public function provide(): array
    {
        return [];
    }
}
