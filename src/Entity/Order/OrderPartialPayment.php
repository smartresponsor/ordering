<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'order_partial_payment')]
class OrderPartialPayment
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'guid')]
    private string $orderId;

    #[ORM\Column(type: 'bigint')]
    private string $amountMinor;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $paidAt;

    #[ORM\Column(type: 'string', length: 64)]
    private string $paymentMethod;

    public function __construct(string $id, string $orderId, int $amountMinor, string $currency, string $paymentMethod)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->id = $id;
        $this->orderId = $orderId;
        $this->amountMinor = (string) $amountMinor;
        $this->currency = $currency;
        $this->paymentMethod = $paymentMethod;
        $this->paidAt = new \DateTimeImmutable('now');
    }

    public function id(): string
    {
        return $this->id;
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
