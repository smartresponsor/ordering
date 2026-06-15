<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_stock_reservation')]
class OrderStockReservationEntity
{
    public const string STATUS_RESERVED = 'reserved';
    public const string STATUS_FAILED = 'failed';
    public const string STATUS_RELEASED = 'released';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private readonly string $orderId;

    #[ORM\Column(length: 64)]
    private readonly string $sku;

    #[ORM\Column(type: 'integer')]
    private readonly int $quantity;

    #[ORM\Column(length: 16)]
    private string $status = self::STATUS_RESERVED;

    public function __construct(string $orderId, string $sku, int $quantity)
    {
        $this->orderId = $orderId;
        $this->sku = $sku;
        $this->quantity = $quantity;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function isReleased(): bool
    {
        return self::STATUS_RELEASED === $this->status;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function release(): void
    {
        $this->markReleased();
    }

    public function markFailed(): void
    {
        $this->status = self::STATUS_FAILED;
    }

    public function markReleased(): void
    {
        $this->status = self::STATUS_RELEASED;
    }
}
