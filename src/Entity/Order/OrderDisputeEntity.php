<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_dispute')]
class OrderDisputeEntity
{
    public const STATUS_OPEN = 'open';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CHARGEBACK_ISSUED = 'chargeback_issued';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderEntity::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private OrderEntity $order;

    #[ORM\Column(type: 'string', length: 64)]
    private string $type;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $reason;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $externalId;

    #[ORM\Column(type: 'string', length: 32)]
    private string $status = self::STATUS_OPEN;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $openedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $resolvedAt = null;

    public function __construct(OrderEntity $order, string $type, ?string $reason = null, ?string $externalId = null)
    {
        $this->order = $order;
        $this->type = $type;
        $this->reason = $reason;
        $this->externalId = $externalId;
        $this->openedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): OrderEntity
    {
        return $this->order;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getOpenedAt(): \DateTimeImmutable
    {
        return $this->openedAt;
    }

    public function getResolvedAt(): ?\DateTimeImmutable
    {
        return $this->resolvedAt;
    }

    public function markResolved(): void
    {
        $this->status = self::STATUS_RESOLVED;
        $this->resolvedAt = new \DateTimeImmutable();
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
