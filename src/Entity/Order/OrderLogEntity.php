<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Ordering\Repository\Order\OrderLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderLogRepository::class)]
#[ORM\Table(name: 'order_log')]
class OrderLogEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderEntity::class)]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?OrderEntity $order = null;

    #[ORM\Column(name: 'order_status_code', type: 'string', length: 64)]
    private string $orderStatusCode;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(name: 'customer_notified', type: 'boolean', options: ['default' => false])]
    private bool $customerNotified = false;

    #[ORM\Column(name: 'o_hash', type: 'string', length: 64, nullable: true)]
    private ?string $objectHash = null;

    public function __construct(string $orderStatusCode, ?OrderEntity $order = null, ?string $comment = null)
    {
        $this->initializeObjectIdentity();
        $this->initializeObjectAudit();
        $this->orderStatusCode = $orderStatusCode;
        $this->order = $order;
        $this->comment = $comment;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderStatusCode(): string
    {
        return $this->orderStatusCode;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function isCustomerNotified(): bool
    {
        return $this->customerNotified;
    }
}
