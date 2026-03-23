<?php

declare(strict_types=1);

namespace App\Strategy\Order;

final class DefaultTaxationStrategy
{
    public function tax(string $taxBase): string
    {
        return number_format(((float) $taxBase) * 0.0, 2, '.', '');
    }
}
