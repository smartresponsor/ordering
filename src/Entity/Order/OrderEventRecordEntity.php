<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_event_record')]
class OrderEventRecordEntity
{
    #[ORM\Id]
    #[ORM\Column(length: 64)]
    private string $eventId;

    #[ORM\Column(length: 64)]
    private string $orderId;

    #[ORM\Column(length: 128)]
    private string $eventName;

    #[ORM\Column(type: 'json')]
    private array $payload = [];

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredAt;

    public function __construct(string $eventId, string $orderId, string $eventName, array $payload = [], ?\DateTimeImmutable $occurredAt = null)
    {
        $this->eventId = $eventId;
        $this->orderId = $orderId;
        $this->eventName = $eventName;
        $this->payload = $payload;
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
