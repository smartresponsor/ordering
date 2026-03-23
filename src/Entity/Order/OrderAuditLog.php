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
#[ORM\Table(name: 'order_audit_log')]
#[ORM\Index(columns: ['order_id'])]
class OrderAuditLog
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(name: 'order_id', type: 'guid')]
    private string $orderId;

    #[ORM\Column(type: 'string', length: 64)]
    private string $action;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $details;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $id, string $orderId, string $action, ?string $details = null, ?\DateTimeImmutable $createdAt = null)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->id = $id;
        $this->orderId = $orderId;
        $this->action = $action;
        $this->details = $details;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function action(): string
    {
        return $this->action;
    }

    public function details(): ?string
    {
        return $this->details;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
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
