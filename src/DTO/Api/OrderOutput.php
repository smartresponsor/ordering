<?php

declare(strict_types=1);

namespace App\DTO\Api;

use App\Ordering\Entity\Order\OrderEntity;

final class OrderOutput
{
    public string $id;
    public string $status;
    public string $grandTotal;

    public static function fromEntity(OrderEntity $order): self
    {
        $dto = new self();
        $dto->id = $order->slug();
        $dto->status = $order->getStatus();
        $dto->grandTotal = $order->getGrandTotal();

        return $dto;
    }
}
