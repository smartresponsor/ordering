<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_webhook_log')]
#[ORM\UniqueConstraint(name: 'uniq_idem_key', columns: ['idempotency_key'])]
class WebhookLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 120, name: 'idempotency_key')]
    private string $idempotencyKey;

    #[ORM\Column(type: 'string', length: 32)]
    private string $eventType;

    #[ORM\Column(type: 'text')]
    private string $payload;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $receivedAt;

    public function __construct(string $key, string $eventType, string $payload)
    {
        $this->idempotencyKey = $key;
        $this->eventType = $eventType;
        $this->payload = $payload;
        $this->receivedAt = new \DateTimeImmutable('now');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function getReceivedAt(): \DateTimeImmutable
    {
        return $this->receivedAt;
    }
}
