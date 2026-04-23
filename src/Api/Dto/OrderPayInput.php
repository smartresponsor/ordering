<?php

declare(strict_types=1);

namespace App\Api\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class OrderPayInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public string $amount = '0.00',
        #[Assert\NotBlank]
        public string $externalRef = 'api',
    ) {
    }
}
