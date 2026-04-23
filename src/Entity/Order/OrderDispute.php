<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Order as RootOrder;

final class OrderDispute
{
    public const string STATUS_OPEN = 'open';
    public const string STATUS_RESOLVED = 'resolved';
    public const string STATUS_CHARGEBACK_ISSUED = 'chargeback_issued';

    private string $status = self::STATUS_OPEN;

    private string $id;

    public function __construct(
        private readonly RootOrder|string $order,
        private readonly string $type,
        private readonly ?string $reason = null,
        private readonly ?string $externalId = null,
    ) {
        $this->id = $this->externalId ?? sha1((is_string($this->order) ? $this->order : $this->order->id()).'|'.$this->type.'|'.($this->reason ?? ''));
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
