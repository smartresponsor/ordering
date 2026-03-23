<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

final class Money
{
    public string $amount;
    public Currency $currency;

    public function __construct(int|string $amount, Currency|string $currency)
    {
        if (is_int($amount)) {
            $amount = number_format($amount / 100, 2, '.', '');
        }

        $amount = (string) $amount;

        if (!preg_match('/^-?\d+(?:\.\d+)?$/', $amount)) {
            throw new \InvalidArgumentException('Invalid decimal for Money: '.$amount);
        }

        $scale = self::scaleOf($amount);

        $this->amount = bcadd($amount, '0', $scale);
        $this->currency = $currency instanceof Currency ? $currency : new Currency($currency);
    }

    public static function zero(Currency|string $currency): self
    {
        return new self('0.00', $currency);
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function currency(): string
    {
        return $this->currency->getCode();
    }

    public function amountMinor(): int
    {
        return (int) round((float) $this->amount * 100, 0);
    }

    public function add(self $other): self
    {
        $this->assertSameCurrency($other);
        $scale = max(self::scaleOf($this->amount), self::scaleOf($other->amount));

        return new self(bcadd($this->amount, $other->amount, $scale), $this->currency);
    }

    public function subtract(self $other): self
    {
        $this->assertSameCurrency($other);
        $scale = max(self::scaleOf($this->amount), self::scaleOf($other->amount));

        return new self(bcsub($this->amount, $other->amount, $scale), $this->currency);
    }

    public function sub(self $other): self
    {
        return $this->subtract($other);
    }

    public function multiply(int|float|string $factor): self
    {
        $factor = is_string($factor) ? $factor : (string) $factor;

        return new self(bcmul($this->amount, $factor, 6), $this->currency);
    }

    public function mul(int|float|string $factor): self
    {
        return $this->multiply($factor);
    }

    public function round(int $scale = 2): self
    {
        return new self(bcadd($this->amount, '0', $scale), $this->currency);
    }

    public function equals(self $other): bool
    {
        return $this->currency->equals($other->currency) && 0 === bccomp($this->amount, $other->amount, 6);
    }

    private static function scaleOf(string $amount): int
    {
        if (!str_contains($amount, '.')) {
            return 2;
        }

        return max(2, strlen(substr(strrchr($amount, '.'), 1)));
    }

    private function assertSameCurrency(self $other): void
    {
        if (!$this->currency->equals($other->currency)) {
            throw new \DomainException('Currency mismatch: '.$this->currency.' vs '.$other->currency);
        }
    }

    public function __toString(): string
    {
        return $this->amount.' '.(string) $this->currency;
    }
}
