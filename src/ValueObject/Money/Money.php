<?php

declare(strict_types=1);

namespace App\Ordering\ValueObject\Money;

final readonly class Money
{
    public function __construct(public int $amount, public Currency $currency)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount must be >= 0');
        }
    }

    public function add(self $other): self
    {
        $this->assertSameCurrency($other);

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function sub(self $other): self
    {
        $this->assertSameCurrency($other);

        return new self(max(0, $this->amount - $other->amount), $this->currency);
    }

    public function mul(float $factor): self
    {
        return new self((int) round($this->amount * $factor), $this->currency);
    }

    public function isZero(): bool
    {
        return 0 === $this->amount;
    }

    private function assertSameCurrency(self $other): void
    {
        if ($this->currency->code !== $other->currency->code) {
            throw new \InvalidArgumentException('Currency mismatch');
        }
    }
}
