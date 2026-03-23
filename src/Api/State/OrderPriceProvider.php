<?php

declare(strict_types=1);

namespace App\Api\State;

final class OrderPriceProvider
{
    public function __construct(private readonly mixed $connection)
    {
    }

    /** @return array<string, mixed> */
    public function provide(): array
    {
        return [];
    }
}
