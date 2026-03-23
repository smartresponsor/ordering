<?php

declare(strict_types=1);

namespace App\Entity\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko / Marketing America Corp <dev@smartresponsor.com>
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\Order\OrderShipmentViewRepository')]
#[ORM\Table(name: 'order_shipment_projection')]
class OrderShipmentView
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 64)]
    private string $orderId;

    #[ORM\Column(type: 'string', length: 16)]
    private string $carrier;

    #[ORM\Column(type: 'string', length: 64)]
    private string $trackingNumber;

    #[ORM\Column(type: 'string', length: 32)]
    private string $status;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deliveredAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function __construct(string $orderId, string $carrier, string $trackingNumber, string $status)
    {
        $this->orderId = $orderId;
        $this->carrier = $carrier;
        $this->trackingNumber = $trackingNumber;
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function update(string $carrier, string $tracking, string $status, ?\DateTimeImmutable $deliveredAt = null): void
    {
        $this->carrier = $carrier;
        $this->trackingNumber = $tracking;
        $this->status = $status;
        $this->deliveredAt = $deliveredAt;
        $this->updatedAt = new \DateTimeImmutable();
    }
}
