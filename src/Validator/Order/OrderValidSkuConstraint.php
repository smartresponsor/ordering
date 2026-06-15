<?php

declare(strict_types=1);

namespace App\Validator\Order;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class OrderValidSkuConstraint extends Constraint
{
    public string $message = 'SKU "{{ string }}" is invalid.';
}
