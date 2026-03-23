<?php

declare(strict_types=1);

namespace App\Entity\Order\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@highhopesamerica.com>.
 */
final class OrderShipmentEntity implements \App\EntityInterface\Order\OrderShipmentEntityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private ?Ulid $id = null;

    public function __construct()
    {
        $this->id ??= new Ulid();
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
