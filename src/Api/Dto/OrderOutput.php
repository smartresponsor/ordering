<?php

declare(strict_types=1);

namespace App\Api\Dto;

use App\Entity\Order;

final class OrderOutput
{
    public int $id;
    public string $status;
    public int $grandTotal;

    public static function fromEntity(Order $order): self
    {
        $dto = new self();
        $dto->id = $order->getId() ?? 0;
        $dto->status = $order->getStatus()->value;
        $dto->grandTotal = $order->getGrandTotal();

        return $dto;
    }
}
