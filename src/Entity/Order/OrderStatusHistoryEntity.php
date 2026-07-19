<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_status_history')]
class OrderStatusHistoryEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderStorageEntity::class, inversedBy: 'statusHistory')]
    #[ORM\JoinColumn(name: 'storage_order_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?OrderStorageEntity $storageOrder = null;

    #[ORM\ManyToOne(targetEntity: OrderEntity::class, inversedBy: 'statusHistory')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private OrderEntity $order;

    #[ORM\Column(type: 'string', length: 32)]
    private string $previousStatus;

    #[ORM\Column(type: 'string', length: 32)]
    private string $newStatus;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $changedAt;

    public function __construct(OrderEntity $order, string $previousStatus, string $newStatus)
    {
        $this->order = $order;
        $this->previousStatus = strtolower($previousStatus);
        $this->newStatus = strtolower($newStatus);
        $this->changedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): OrderEntity
    {
        return $this->order;
    }

    public function getPreviousStatus(): string
    {
        return $this->previousStatus;
    }

    public function getNewStatus(): string
    {
        return $this->newStatus;
    }

    public function getChangedAt(): \DateTimeImmutable
    {
        return $this->changedAt;
    }
}
