<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: 'App\Repository\Order\OrderPaymentViewRepository')]
#[ORM\Table(name: 'order_payment_projection')]
class OrderPaymentView
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private ?Ulid $id = null;

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 64)]
    private string $orderId;

    #[ORM\Column(type: 'string', length: 64)]
    private string $paymentId;

    #[ORM\Column(type: 'integer')]
    private int $amountMinor;

    #[ORM\Column(type: 'string', length: 8)]
    private string $currency;

    #[ORM\Column(type: 'string', length: 32)]
    private string $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function __construct(string $orderId, string $paymentId, int $amountMinor, string $currency, string $status)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->orderId = $orderId;
        $this->paymentId = $paymentId;
        $this->amountMinor = $amountMinor;
        $this->currency = $currency;
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updateStatus(string $status): void
    {
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
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
