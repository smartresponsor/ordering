<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

final class Discount
{
    private ?string $percent;
    private ?Money $fixed;

    public function __construct(int|float|string|null $percent = null, ?Money $fixed = null)
    {
        if (null !== $percent && null !== $fixed) {
            throw new \DomainException('Discount cannot be both percentage and fixed.');
        }

        if (null === $percent && null === $fixed) {
            throw new \DomainException('Discount must be percentage or fixed.');
        }

        $this->percent = null !== $percent ? self::normalizePercent($percent) : null;
        $this->fixed = $fixed;
    }

    public static function percent(int|float|string $percent): self
    {
        return new self($percent, null);
    }

    public static function fixed(Money $money): self
    {
        return new self(null, $money);
    }

    public function apply(Money $base): Money
    {
        if (null !== $this->percent) {
            $factor = bcdiv($this->percent, '100', 6);
            $result = $base->multiply(bcsub('1', $factor, 6));

            return $result->round(self::moneyScale($base));
        }
        if (null !== $this->fixed) {
            if (!$base->getCurrency()->equals($this->fixed->getCurrency())) {
                throw new \DomainException('Currency mismatch in Discount');
            }

            return $base->subtract($this->fixed);
        }

        return $base;
    }

    private static function moneyScale(Money $money): int
    {
        $amount = $money->getAmount();

        if (!str_contains($amount, '.')) {
            return 2;
        }

        return max(2, strlen(substr(strrchr($amount, '.'), 1)));
    }

    private static function normalizePercent(int|float|string $percent): string
    {
        $raw = is_string($percent) ? $percent : (string) $percent;

        if (!preg_match('/^-?\d+(?:\.\d+)?$/', $raw)) {
            throw new \DomainException('Invalid discount percent: '.$raw);
        }

        $normalized = bcadd($raw, '0', 6);

        if (bccomp($normalized, '1', 6) <= 0 && bccomp($normalized, '-1', 6) >= 0) {
            $normalized = bcmul($normalized, '100', 6);
        }

        return $normalized;
    }
}
