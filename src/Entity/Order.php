<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Api\Controller\OrderPayController;
use App\Api\Controller\OrderShipController;
use App\Api\Dto\OrderInput;
use App\Api\Dto\OrderOutput;
use App\Entity\Common\ObjectAuditTrait;
use App\ValueObject\Money\Currency;
use App\ValueObject\Order\OrderStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
#[ApiResource(
    operations: [
        new Get(uriTemplate: '/orders/{id}', normalizationContext: ['groups' => ['order:read']]),
        new Post(uriTemplate: '/orders', input: OrderInput::class, output: OrderOutput::class),
        new Post(uriTemplate: '/orders/{id}/pay', controller: OrderPayController::class),
        new Post(uriTemplate: '/orders/{id}/ship', controller: OrderShipController::class),
    ]
)]
class Order
{
    use ObjectAuditTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 3)]
    #[Groups(['order:read'])]
    private string $currency = 'USD';

    #[ORM\Column(type: 'string', length: 16)]
    #[Groups(['order:read'])]
    private string $status = OrderStatus::Draft->value;

    #[ORM\Column(type: 'integer')]
    #[Groups(['order:read'])]
    private int $subtotal = 0;
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:read'])]
    private int $discountTotal = 0;
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:read'])]
    private int $taxTotal = 0;
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:read'])]
    private int $grandTotal = 0;

    public function __construct()
    {
        $this->initAudit();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): Currency
    {
        return new Currency($this->currency);
    }

    public function setCurrency(Currency $c): void
    {
        $this->currency = (string) $c;
    }

    public function getStatus(): OrderStatus
    {
        return OrderStatus::from($this->status);
    }

    public function setStatus(OrderStatus $s): void
    {
        $this->status = $s->value;
    }

    public function setTotals(int $subtotal, int $discount, int $tax, int $grand): void
    {
        $this->subtotal = $subtotal;
        $this->discountTotal = $discount;
        $this->taxTotal = $tax;
        $this->grandTotal = $grand;
    }

    public function getSubtotal(): int
    {
        return $this->subtotal;
    }

    public function getDiscountTotal(): int
    {
        return $this->discountTotal;
    }

    public function getTaxTotal(): int
    {
        return $this->taxTotal;
    }

    public function getGrandTotal(): int
    {
        return $this->grandTotal;
    }
}
