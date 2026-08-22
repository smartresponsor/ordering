<?php

declare(strict_types=1);

namespace App\Ordering\ValueObject\Pricing\Order;

final class RefundAmount extends Money
{
    public static function fromMoney(Money $money): self
    {
        return new self($money->getAmount(), $money->getCurrency());
    }
}
