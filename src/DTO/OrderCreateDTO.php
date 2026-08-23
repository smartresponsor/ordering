<?php

declare(strict_types=1);

namespace App\Ordering\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderCreateDTO
{
    #[Assert\NotBlank]
    #[Assert\Currency]
    public string $currency = 'USD';

    #[Assert\NotBlank]
    #[Assert\Positive]
    public string $grandTotal = '0.00';
}
