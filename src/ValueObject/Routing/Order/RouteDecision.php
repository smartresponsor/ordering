<?php

declare(strict_types=1);

namespace App\ValueObject\Routing\Order;


final readonly class RouteDecision
{
    private string $provider;
    private float $score;

    public function __construct(string $provider, float $score)
    {
        $this->provider = $provider;
        $this->score = $score;
    }

    public function provider(): string
    {
        return $this->provider;
    }

    public function score(): float
    {
        return $this->score;
    }
}
