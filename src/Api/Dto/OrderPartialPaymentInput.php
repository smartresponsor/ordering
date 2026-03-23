<?php

declare(strict_types=1);

namespace App\Api\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderPartialPaymentInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public string $amount = '0.00',
        public ?string $externalRef = null,
    ) {
    }
}
