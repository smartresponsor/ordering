<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Order as RootOrder;

final class OrderDispute
{
    public const STATUS_OPEN = 'open';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CHARGEBACK_ISSUED = 'chargeback_issued';

    private string $status = self::STATUS_OPEN;

    private string $id;

    public function __construct(
        private RootOrder|string $order,
        private string $type,
        private ?string $reason = null,
        private ?string $externalId = null,
    ) {
        $this->id = $this->externalId ?? sha1((is_string($this->order) ? $this->order : (string) $this->order->id()).'|'.$this->type.'|'.($this->reason ?? ''));
    }

    public function resolve(): void
    {
        $this->markResolved();
    }

    public function markResolved(): void
    {
        $this->status = self::STATUS_RESOLVED;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getOrder(): RootOrder|string
    {
        return $this->order;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }
}
