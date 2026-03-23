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
#[ORM\Table(name: 'order_item')]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private object $order;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private string $basePrice;

    #[ORM\Column(length: 3)]
    private string $currency;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    private string $finalPrice = '0.00';

    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private float $taxRate = 0.0;

    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private float $discountPercent = 0.0;

    public function __construct(object $order, string $basePrice, string $currency)
    {
        $this->order = $order;
        $this->basePrice = $basePrice;
        $this->currency = $currency;
    }

    public function getOrder(): object
    {
        return $this->order;
    }

    public function getBasePrice(): string
    {
        return $this->basePrice;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setFinalPrice(string $price): void
    {
        $this->finalPrice = $price;
    }

    public function getFinalPrice(): string
    {
        return $this->finalPrice;
    }

    public function setTaxRate(float $r): void
    {
        $this->taxRate = $r;
    }

    public function setDiscountPercent(float $p): void
    {
        $this->discountPercent = $p;
    }

    public function getId(): ?Ulid
    {
        return null;
    }

    public function setId(Ulid $id): self
    {
        return $this;
    }
}
