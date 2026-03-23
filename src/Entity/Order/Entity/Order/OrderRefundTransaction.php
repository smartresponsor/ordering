<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order\Entity\Order;

use App\ValueObject\Order\RefundStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: 'App\Repository\Order\OrderRefundTransactionRepository')]
#[ORM\Table(name: 'order_refund_transaction')]
class OrderRefundTransaction
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 64)]
    private string $id;

    #[ORM\Column(type: 'string', length: 64)]
    private string $orderId;

    #[ORM\Column(type: 'string', length: 64)]
    private string $returnRequestId;

    #[ORM\Column(type: 'integer')]
    private int $amountMinor;

    #[ORM\Column(type: 'string', length: 8)]
    private string $currency;

    #[ORM\Column(type: 'string', length: 64)]
    private string $paymentId;

    #[ORM\Column(type: 'string', length: 32)]
    private string $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $id, string $orderId, string $returnRequestId, string $paymentId, int $amountMinor, string $currency)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->id = $id;
        $this->orderId = $orderId;
        $this->returnRequestId = $returnRequestId;
        $this->paymentId = $paymentId;
        $this->amountMinor = $amountMinor;
        $this->currency = $currency;
        $this->status = RefundStatus::PROCESSING->value;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function amountMinor(): int
    {
        return $this->amountMinor;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function paymentId(): string
    {
        return $this->paymentId;
    }

    public function markCompleted(): void
    {
        $this->status = RefundStatus::COMPLETED->value;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): self
    {
        $this->id = $id;

        return $this;
    }
}
