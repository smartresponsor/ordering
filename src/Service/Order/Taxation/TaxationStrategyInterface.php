<?php

declare(strict_types=1);

namespace App\Service\Order\Taxation;

use App\ValueObject\Order\Money;

interface TaxationStrategyInterface
{
    public function tax(Money $taxableBase): Money;
}
