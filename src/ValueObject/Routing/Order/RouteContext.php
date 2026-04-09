<?php

declare(strict_types=1);

namespace App\ValueObject\Routing\Order;


final readonly class RouteContext
{
    private string $intentId;
    private string $region;
    private float $amountUsd;
    private bool $canary;

    public function __construct(string $intentId, string $region, float $amountUsd, bool $canary)
    {
        $this->intentId = $intentId;
        $this->region = $region;
        $this->amountUsd = $amountUsd;
        $this->canary = $canary;
    }

    public function intentId(): string
    {
        return $this->intentId;
    }

    public function region(): string
    {
        return $this->region;
    }

    public function amountUsd(): float
    {
        return $this->amountUsd;
    }

    public function canary(): bool
    {
        return $this->canary;
    }
}
