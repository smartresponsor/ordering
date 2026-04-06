<?php

declare(strict_types=1);

namespace App\Api\Dto;

use App\Entity\Order;

final class OrderOutput
{
    public string $id;
    public string $status;
    public string $grandTotal;

    public static function fromEntity(Order $order): self
    {
        $dto = new self();
        $dto->id = $order->getId();
        $dto->status = $order->getStatus();
        $dto->grandTotal = $order->getGrandTotal();

        return $dto;
    }
}
