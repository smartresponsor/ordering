<?php

declare(strict_types=1);

namespace App\Ordering\Validator\Order;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class OrderValidSkuConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!preg_match('/^[A-Z0-9\-_.]{2,64}$/', (string) $value)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', (string) $value)
                ->addViolation();
        }
    }
}
