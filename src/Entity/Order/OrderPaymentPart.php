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
#[ORM\Table(name: 'order_payment_part')]
class OrderPaymentPart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 36)]
    private string $orderId;

    #[ORM\Column(type: 'string', length: 64)]
    private string $paymentId;

    #[ORM\Column(type: 'string', length: 16)]
    private string $currency;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private string $amount;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $orderId, string $paymentId, string $currency, string $amount)
    {
        $this->orderId = $orderId;
        $this->paymentId = $paymentId;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
