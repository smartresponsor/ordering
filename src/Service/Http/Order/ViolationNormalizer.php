<?php

declare(strict_types=1);

namespace App\Service\Http\Order;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ViolationNormalizer
{
    /** @return array<int, array<string, string|null>> */
    public function normalize(ConstraintViolationListInterface|array $violations): array
    {
        $items = [];
        foreach ($violations as $violation) {
            $items[] = [
                'path' => method_exists($violation, 'getPropertyPath') ? $violation->getPropertyPath() : null,
                'message' => method_exists($violation, 'getMessage') ? (string) $violation->getMessage() : 'Validation failed.',
                'code' => method_exists($violation, 'getCode') ? (string) $violation->getCode() : null,
            ];
        }

        return $items;
    }
}
