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
#[ORM\Table(name: 'order_return_policy')]
#[ORM\UniqueConstraint(name: 'uniq_policy_shipment', columns: ['shipment_id'])]
class OrderReturnPolicy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: OrderShipment::class)]
    #[ORM\JoinColumn(name: 'shipment_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private OrderShipment $shipment;

    #[ORM\Column(type: 'integer', options: ['default' => 14])]
    private int $daysAllowed = 14;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $autoExpireDate = null;

    public function __construct(OrderShipment $shipment, int $daysAllowed = 14)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->shipment = $shipment;
        $this->daysAllowed = $daysAllowed;
        if ($shipment->getDeliveredAt()) {
            $this->autoExpireDate = $shipment->getDeliveredAt()->modify('+'.$daysAllowed.' days');
        }
    }

    public function getAutoExpireDate(): ?\DateTimeImmutable
    {
        return $this->autoExpireDate;
    }

    public function getDaysAllowed(): int
    {
        return $this->daysAllowed;
    }

    public function setDeliveredRecalculate(): void
    {
        if ($this->shipment->getDeliveredAt()) {
            $this->autoExpireDate = $this->shipment->getDeliveredAt()->modify('+'.$this->daysAllowed.' days');
        }
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
