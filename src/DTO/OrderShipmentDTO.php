<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderShipmentDTO
{
    #[Assert\NotBlank]
    public string $carrier = 'UPS';

    #[Assert\Length(max: 255)]
    public ?string $note = null;
}
