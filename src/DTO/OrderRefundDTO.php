<?php

declare(strict_types=1);

namespace App\Ordering\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderRefundDTO
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public string $amount = '0.00';

    #[Assert\Length(max: 128)]
    public ?string $reason = null;
}
