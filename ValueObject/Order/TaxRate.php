<?php

declare(strict_types=1);

namespace App\ValueObject\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class TaxRate
{
    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private string $percent;

    public function __construct(string $percent)
    {
        $this->percent = $percent;
    }

    public static function of(string $percent): self
    {
        return new self($percent);
    }

    public function asDecimal(): string
    {
        return $this->percent;
    }
}
