<?php

declare(strict_types=1);

namespace App\Api\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class OrderShipInput
{
    public function __construct(
        #[Assert\Positive]
        public int $count = 1,
        public ?string $note = null,
    ) {
    }
}
