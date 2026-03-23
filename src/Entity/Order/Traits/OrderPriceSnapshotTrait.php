<?php

declare(strict_types=1);

namespace App\Entity\Order\Traits;

use App\Entity\Order\OrderPriceDetail;
use Doctrine\ORM\Mapping as ORM;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@highhopesamerica.com>.
 */
trait OrderPriceSnapshotTrait
{
    #[ORM\OneToOne(targetEntity: OrderPriceDetail::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'price_detail_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?OrderPriceDetail $priceDetail = null;

    public function priceDetail(): ?OrderPriceDetail
    {
        return $this->priceDetail;
    }

    public function setPriceDetail(?OrderPriceDetail $detail): void
    {
        $this->priceDetail = $detail;
    }
}
