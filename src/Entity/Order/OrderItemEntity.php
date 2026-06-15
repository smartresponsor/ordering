<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\Quantity;
use App\ValueObject\Pricing\Order\Sku;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_item')]
class OrderItemEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderEntity::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?OrderEntity $order;

    #[ORM\Column(type: 'string', length: 128)]
    private string $sku;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $basePrice;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $discount = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $tax = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $finalPrice = 0;

    public function __construct(mixed $arg1 = 'SKU-1', mixed $arg2 = 1, mixed $arg3 = '0.00', mixed $arg4 = 'USD')
    {
        if ($arg1 instanceof OrderEntity) {
            $this->order = $arg1;
            $sku = $arg2;
            $quantity = $arg3;
            $basePrice = $arg4;
            $currency = 'USD';
        } else {
            $this->order = null;
            $sku = $arg1;
            $quantity = $arg2;
            $basePrice = $arg3;
            $currency = $arg4;
        }

        if ($sku instanceof Sku) {
            $this->sku = (string) $sku;
        } elseif (is_scalar($sku)) {
            $this->sku = (string) $sku;
        } else {
            $this->sku = 'SKU-1';
        }

        if ($quantity instanceof Quantity) {
            $this->quantity = $quantity->toInt();
        } elseif (is_scalar($quantity) && is_numeric($quantity)) {
            $this->quantity = max(1, (int) $quantity);
        } else {
            $this->quantity = 1;
        }

        if (is_object($basePrice) && method_exists($basePrice, 'getAmount') && method_exists($basePrice, 'getCurrency')) {
            $this->basePrice = number_format((float) $basePrice->getAmount(), 2, '.', '');
            $this->currency = strtoupper((string) $basePrice->getCurrency());

            return;
        }

        if (is_int($basePrice)) {
            $this->basePrice = number_format($basePrice / 100, 2, '.', '');
        } elseif (is_scalar($basePrice)) {
            $this->basePrice = number_format((float) $basePrice, 2, '.', '');
        } else {
            $this->basePrice = '0.00';
        }
        $this->currency = is_scalar($currency) ? strtoupper((string) $currency) : 'USD';
        $this->recomputeLineTotals();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?OrderEntity
    {
        return $this->order;
    }

    public function setOrder(?OrderEntity $order): void
    {
        $this->order = $order;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getBasePrice(): string
    {
        return $this->basePrice;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getUnitPrice(): string
    {
        return $this->basePrice;
    }

    public function subtotalMoney(): Money
    {
        return new Money(number_format(((float) $this->basePrice) * $this->quantity, 2, '.', ''), $this->currency);
    }

    public function setPricingBreakdown(int $discount, int $tax, int $finalPrice): void
    {
        $this->discount = max(0, $discount);
        $this->tax = max(0, $tax);
        $this->finalPrice = max(0, $finalPrice);
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function getTax(): int
    {
        return $this->tax;
    }

    public function getFinalPrice(): int
    {
        return $this->finalPrice;
    }

    private function recomputeLineTotals(): void
    {
        $minor = (int) round(((float) $this->basePrice) * 100) * $this->quantity;
        $this->finalPrice = $minor;
    }
}
