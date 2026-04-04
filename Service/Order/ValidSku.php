<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidSku extends Constraint
{
    public string $message = 'SKU "{{ string }}" is invalid.';
}
