<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class Payment
{
    public function __construct(private string $state = 'pending') {}
    public function state(): string { return $this->state; }
    public function markPaid(): void { $this->state = 'paid'; }
}
