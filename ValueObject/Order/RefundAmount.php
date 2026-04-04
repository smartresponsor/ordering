<?php

declare(strict_types=1);

namespace App\ValueObject\Security\Order;

use App\ValueObject\Order\Money;

final class RefundAmount extends Money
{
    public static function fromMoney(Money $money): self
    {
        return new self($money->getAmount(), $money->getCurrency());
    }
}
