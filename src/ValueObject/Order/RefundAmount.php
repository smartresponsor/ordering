<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

final class RefundAmount extends Money
{
    public static function fromMoney(Money $money): self
    {
        return new self($money->getAmount(), $money->getCurrency());
    }

    public function getAmountString(): string
    {
        return $this->getAmount();
    }

    public function getCurrencyCode(): string
    {
        return (string) $this->getCurrency();
    }
}
