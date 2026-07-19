<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use App\Ordering\Entity\Order\OrderEntity as RootOrderEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'inventory_reservation')]
final class OrderInventoryReservationEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public const string STATE_RESERVED = 'reserved';
    public const string STATE_RELEASED = 'released';
    public const string STATE_CONSUMED = 'consumed';
    public const string STATE_FAILED = 'failed';

    #[ORM\Column(length: 64)]
    private string $orderId;

    #[ORM\Column(length: 128, unique: true)]
    private string $reservationKey;

    /** @var array<string, int> */
    #[ORM\Column(type: 'json')]
    private array $lines;

    #[ORM\Column(length: 16)]
    private string $state = self::STATE_RESERVED;

    /**
     * @param array<string, int>|string $reservationKeyOrSku
     * @param array<string, int>|int    $linesOrQuantity
     */
    public function __construct(
        private readonly RootOrderEntity|string $order,
        array|string $reservationKeyOrSku,
        array|int $linesOrQuantity,
    ) {
        if ($order instanceof RootOrderEntity) {
            $this->orderId = $order->id();
            $this->reservationKey = (string) $reservationKeyOrSku;
            $this->lines = is_array($linesOrQuantity) ? $linesOrQuantity : [];

            return;
        }

        $sku = (string) $reservationKeyOrSku;
        $quantity = (int) $linesOrQuantity;

        $this->orderId = $order;
        $this->reservationKey = $order.':'.$sku;
        $this->lines = [$sku => $quantity];
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function sku(): string
    {
        return (string) array_key_first($this->lines);
    }

    public function quantity(): int
    {
        return (int) ($this->lines[$this->sku()] ?? 0);
    }

    public function getReservationKey(): string
    {
        return $this->reservationKey;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function markReleased(): void
    {
        $this->state = self::STATE_RELEASED;
    }

    public function markConsumed(): void
    {
        $this->state = self::STATE_CONSUMED;
    }

    public function markFailed(): void
    {
        $this->state = self::STATE_FAILED;
    }
}
