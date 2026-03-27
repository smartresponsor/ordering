<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

class Money
{
    private string $amount;
    private Currency $currency;

    public function __construct(string $amount, Currency|string $currency)
    {
        if (!preg_match('/^-?\d+(?:\.\d+)?$/', $amount)) {
            throw new \InvalidArgumentException('Invalid decimal for Money: '.$amount);
        }

        $this->amount = $amount;
        $this->currency = $currency instanceof Currency ? $currency : new Currency($currency);
    }

    public static function zero(Currency|string $currency): self
    {
        return new self('0.00', $currency);
    }

    public static function assertSameCurrency(self $left, self $right): void
    {
        if (!$left->currency->equals($right->currency)) {
            throw new \DomainException('Currency mismatch: '.$left->currency.' vs '.$right->currency);
        }
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function add(self $other): self
    {
        self::assertSameCurrency($this, $other);

        return new self(bcadd($this->amount, $other->amount, 6), $this->currency);
    }

    public function sub(self $other): self
    {
        return $this->subtract($other);
    }

    public function subtract(self $other): self
    {
        self::assertSameCurrency($this, $other);

        return new self(bcsub($this->amount, $other->amount, 6), $this->currency);
    }

    public function multiply(string $factor): self
    {
        return new self(bcmul($this->amount, $factor, 6), $this->currency);
    }

    public function round(int $scale = 2): self
    {
        return new self(bcadd($this->amount, '0', $scale), $this->currency);
    }

    public function equals(self $other): bool
    {
        return $this->currency->equals($other->currency) && 0 === bccomp($this->amount, $other->amount, 6);
    }

    public function __toString(): string
    {
        return $this->amount.' '.(string) $this->currency;
    }
}
