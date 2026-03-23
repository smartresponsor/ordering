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
#[ORM\Table(name: 'order_archive')]
#[ORM\Index(columns: ['order_number', 'archived_at'], name: 'idx_order_archive_number_at')]
class OrderArchive
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'order_number', length: 64)]
    private string $orderNumber;

    #[ORM\Column(type: 'json')]
    private array $snapshot;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $archivedAt;

    public function __construct(string $orderNumber, array $snapshot)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->orderNumber = $orderNumber;
        $this->snapshot = $snapshot;
        $this->archivedAt = new \DateTimeImmutable('now');
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
