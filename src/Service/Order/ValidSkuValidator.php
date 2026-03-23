<?php

declare(strict_types=1);

namespace App\Service\Order;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ValidSkuValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }
        if (!preg_match('/^[A-Z0-9\-_.]{2,64}$/', (string) $value)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', (string) $value)->addViolation();
        }
    }
}
