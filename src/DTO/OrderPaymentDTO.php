<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderPaymentDTO
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public string $amount = '0.00';

    #[Assert\NotBlank]
    public string $externalRef = '';
}
