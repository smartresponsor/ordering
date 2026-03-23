<?php

declare(strict_types=1);

namespace App\DTO\Order;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderShipmentDTO
{
    public function __construct(
        #[Assert\Positive]
        public int $count,
        public ?string $note = null,
    ) {
    }
}
