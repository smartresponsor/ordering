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
#[ORM\Table(name: 'order_price_audit')]
class OrderPriceAudit
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'guid')]
    private string $orderId;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredAt;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency;

    #[ORM\Column(type: 'bigint')]
    private string $subtotalMinor;

    #[ORM\Column(type: 'bigint')]
    private string $discountMinor;

    #[ORM\Column(type: 'bigint')]
    private string $taxMinor;

    #[ORM\Column(type: 'bigint')]
    private string $totalMinor;

    #[ORM\Column(type: 'string', length: 128)]
    private string $reason;

    public function __construct(string $id, string $orderId, string $currency, int $subtotalMinor, int $discountMinor, int $taxMinor, int $totalMinor, string $reason)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->id = $id;
        $this->orderId = $orderId;
        $this->occurredAt = new \DateTimeImmutable('now');
        $this->currency = $currency;
        $this->subtotalMinor = (string) $subtotalMinor;
        $this->discountMinor = (string) $discountMinor;
        $this->taxMinor = (string) $taxMinor;
        $this->totalMinor = (string) $totalMinor;
        $this->reason = $reason;
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
