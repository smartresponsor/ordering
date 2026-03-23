<?php

declare(strict_types=1);

namespace App\Entity\Order\Entity\Order;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@highhopesamerica.com>.
 */
final class Order
{
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;
    private array $meta;

    public function __construct(
        private string $id,
        private int $totalAmount,
        private string $currency,
        private string $customerId,
        array $meta = [],
        private string $status = 'draft',
    ) {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
        $this->meta = $meta;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
    }
}
